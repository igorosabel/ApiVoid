<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;

class TokenService extends OService {
  /**
   * Devuelve el TTL del access token en segundos.
   *
   * @return int Segundos de vida del access token
   */
  public function getAccessTokenTtl(): int {
    return $this->getConfig()->getExtra('access_ttl');
  }

  /**
   * Crea un access token (JWT HS256) para un jugador.
   *
   * @param int $id_player Id del jugador
   * @return string Token JWT
   */
  public function createAccessToken(int $id_player): string {
    $header  = $this->b64(json_encode(['alg'=>'HS256','typ'=>'JWT']));
    $now     = time();
    $payload = $this->b64(json_encode([
      'sub' => $id_player,
      'iat' => $now,
      'exp' => $now + $this->getAccessTokenTtl(),
      'scope' => 'player'
    ]));
    $sig = $this->sign("{$header}.{$payload}");
    return "{$header}.{$payload}.{$sig}";
  }

  /**
   * Firma HS256 base64url.
   *
   * @param string $data Datos a firmar
   * @return string Firma base64url
   */
  private function sign(string $data): string {
    return $this->b64(hash_hmac('sha256', $data, $this->getConfig()->getExtra('secret'), true));
  }

  /**
   * Base64 url-safe sin padding.
   *
   * @param string $raw Cadena a codificar
   * @return string Cadena codificada
   */
  private function b64(string $raw): string {
    return rtrim(strtr(base64_encode($raw), '+/', '-_'), '=');
  }
}
