<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Service\TokenService;
use Osumi\OsumiFramework\App\Model\AuthRefreshToken;

/**
 * Servicio de autenticación: refresco y cierre de sesión.
 */
class AuthService extends OService {
  private ?TokenService $token_service = null;

	function __construct() {
		$this->token_service = inject(TokenService::class);
	}

  /**
   * Realiza el login de un jugador: valida credenciales, emite tokens
   * y devuelve datos del jugador, sistema actual y nave activa (última creada).
   *
   * @param string $email Email del jugador
   * @param string $password Contraseña en claro
   * @return array{
   *   player: array{id:int,nickname:string},
   *   system: array{id:int,original_name:string}|null,
   *   ship:   array{id:int,id_ship_type:int}|null,
   *   tokens: array{access_token:string,expires_in:int,refresh_token:string}
   * }
   *
   * @throws \RuntimeException INVALID_CREDENTIALS | TOKEN_SERVICE_NOT_CONFIGURED
   */
  public function login(string $email, string $password): array {
    $db = new ODB();

    // 1) Buscar jugador por email
    $db->query(
      'SELECT id, password_hash, nickname
         FROM player
        WHERE email = :email
        LIMIT 1',
      [':email' => $email]
    );
    if ($db->count() === 0) {
      throw new \RuntimeException('INVALID_CREDENTIALS');
    }
    $row = $db->next();
    $id_player     = (int) $row['id'];
    $password_hash = (string) $row['password_hash'];
    $nickname      = (string) $row['nickname'];

    // 2) Verificar contraseña
    if (!password_verify($password, $password_hash)) {
      throw new \RuntimeException('INVALID_CREDENTIALS');
    }

    if ($this->token_service === null) {
      throw new \RuntimeException('TOKEN_SERVICE_NOT_CONFIGURED');
    }

    // 3) Sistema actual (presencia) o casa (home)
    $system = null;
    $db->query(
      'SELECT id_system FROM player_presence WHERE id_player = :p LIMIT 1',
      [':p' => $id_player]
    );
    if ($db->count() === 0) {
      $db->query(
        'SELECT id_system FROM player_system_home WHERE id_player = :p LIMIT 1',
        [':p' => $id_player]
      );
    }
    if ($db->count() > 0) {
      $row_sys = $db->next();
      $id_system = (int) $row_sys['id_system'];
      $db->query(
        'SELECT id, original_name FROM system WHERE id = :s LIMIT 1',
        [':s' => $id_system]
      );
      if ($db->count() > 0) {
        $rs = $db->next();
        $system = [
          'id'            => (int) $rs['id'],
          'original_name' => (string) $rs['original_name']
        ];
      }
    }

    // 4) Nave activa (última creada)
    $ship = null;
    $db->query(
      'SELECT id, id_ship_type
         FROM player_ship
        WHERE id_player = :p
        ORDER BY id DESC
        LIMIT 1',
      [':p' => $id_player]
    );
    if ($db->count() > 0) {
      $rs = $db->next();
      $ship = [
        'id'           => (int) $rs['id'],
        'id_ship_type' => (int) $rs['id_ship_type']
      ];
    }

    // 5) Tokens
    $access_token  = $this->token_service->createAccessToken($id_player);
    $expires_in    = $this->token_service->getAccessTokenTtl();

    $refresh_token = bin2hex(random_bytes(32));
    $art = AuthRefreshToken::create();
    $art->id_player  = $id_player;
    $art->token      = $refresh_token;
    $art->expires_at = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 30)); // 30 días
    $art->revoked    = false;
    $art->save();

    return [
      'player' => ['id' => $id_player, 'nickname' => $nickname],
      'system' => $system,
      'ship'   => $ship,
      'tokens' => [
        'access_token'  => $access_token,
        'expires_in'    => $expires_in,
        'refresh_token' => $refresh_token
      ]
    ];
  }

  /**
   * Refresca el access token usando un refresh token válido.
   * Aplica rotación de refresh token: revoca el actual y emite uno nuevo.
   *
   * @param string $refresh_token Refresh token recibido del cliente
   * @return array{access_token:string,expires_in:int,refresh_token:string} Tokens nuevos
   *
   * @throws \RuntimeException INVALID_REFRESH_TOKEN | REFRESH_TOKEN_REVOKED | REFRESH_TOKEN_EXPIRED | TOKEN_SERVICE_NOT_CONFIGURED
   */
  public function refreshTokens(string $refresh_token): array {
    $db = new ODB();

    // 1) Buscar token
    $db->query(
      'SELECT `id`, `id_player`, `expires_at`, `revoked`
         FROM `auth_refresh_token`
        WHERE `token` = :t
        LIMIT 1',
      ['t' => $refresh_token]
    );
    if ($db->count() === 0) {
      throw new \RuntimeException('INVALID_REFRESH_TOKEN');
    }
    $row = $db->next();

    // 2) Validaciones
    if ((int) $row['revoked'] === 1) {
      throw new \RuntimeException('REFRESH_TOKEN_REVOKED');
    }
    $expires_at_ts = strtotime((string) $row['expires_at']);
    if ($expires_at_ts !== false && $expires_at_ts < time()) {
      throw new \RuntimeException('REFRESH_TOKEN_EXPIRED');
    }
    $id_player = (int) $row['id_player'];

    // 3) Rotar refresh token: revocar el actual y crear uno nuevo
    $db->query(
      'UPDATE `auth_refresh_token`
          SET `revoked` = 1, `updated_at` = NOW()
        WHERE `id` = :id',
      ['id' => (int) $row['id']]
    );

    $new_refresh_token = bin2hex(random_bytes(32));
    $art = AuthRefreshToken::create();
    $art->id_player  = $id_player;
    $art->token      = $new_refresh_token;
    $art->expires_at = date('Y-m-d H:i:s', time() + (60 * 60 * 24 * 30)); // 30 días
    $art->revoked    = 0;
    $art->save();

    // 4) Emitir nuevo access token
    $access_token = $this->token_service->createAccessToken($id_player);
    $expires_in   = $this->token_service->getAccessTokenTtl();

    return [
      'access_token'  => $access_token,
      'expires_in'    => $expires_in,
      'refresh_token' => $new_refresh_token
    ];
  }

  /**
   * Cierra sesión revocando el refresh token. Si $all_devices es true,
   * revoca todos los tokens del jugador propietario.
   *
   * @param string $refresh_token Refresh token a revocar
   * @param bool $all_devices Si true, revoca todos los tokens del jugador
   * @return void
   *
   * @throws \RuntimeException INVALID_REFRESH_TOKEN
   */
  public function logout(string $refresh_token, bool $all_devices = false): void {
    $db = new ODB();

    // 1) Obtener el token para identificar al jugador
    $db->query(
      'SELECT `id`, `id_player`
         FROM `auth_refresh_token`
        WHERE `token` = :t
        LIMIT 1',
      ['t' => $refresh_token]
    );
    if ($db->count() === 0) {
      throw new \RuntimeException('INVALID_REFRESH_TOKEN');
    }
    $row = $db->next();
    $id_player = (int) $row['id_player'];

    // 2) Revocación
    if ($all_devices) {
      $db->query(
        'UPDATE `auth_refresh_token`
            SET `revoked` = 1, `updated_at` = NOW()
          WHERE `id_player` = :p AND `revoked` = 0',
        ['p' => $id_player]
      );
    } else {
      $db->query(
        'UPDATE `auth_refresh_token`
            SET `revoked` = 1, `updated_at` = NOW()
          WHERE `token` = :t',
        ['t' => $refresh_token]
      );
    }
  }
}
