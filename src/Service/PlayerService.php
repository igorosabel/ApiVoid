<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\ORM\ODB;
use Osumi\OsumiFramework\App\Service\TokenService;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\PlayerSystemHome;
use Osumi\OsumiFramework\App\Model\PlayerPresence;
use Osumi\OsumiFramework\App\Model\PlayerShip;
use Osumi\OsumiFramework\App\Model\System;
use Osumi\OsumiFramework\App\Model\Planet;
use Osumi\OsumiFramework\App\Model\Moon;
use Osumi\OsumiFramework\App\Model\LocationResource;
use Osumi\OsumiFramework\App\Model\Merchant;
use Osumi\OsumiFramework\App\Model\MerchantStockResource;
use Osumi\OsumiFramework\App\Model\MerchantStockModule;
use Osumi\OsumiFramework\App\Model\AuthRefreshToken;

class PlayerService extends OService {
	private ?TokenService $token_service = null;

	function __construct() {
		$this->token_service = inject(TokenService::class);
	}

	/**
	 * Obtiene un jugador mediante su email
	 *
	 * @param string $email Email del jugador
	 *
	 * @return Player | null Jugador solicitado o null si no se encuentra
	 */
	public function getPlayerByEmail(string $email): Player | null {
		return Player::findOne(['email' => $email]);
	}

	/**
	 * Obtiene un jugador mediante su nickname
	 *
	 * @param string $nickname Nickname del jugador
	 *
	 * @return Player | null Jugador solicitado o null si no se encuentra
	 */
	public function getPlayerByNickname(string $nickname): Player | null {
		return Player::findOne(['nickname' => $nickname]);
	}

	/**
   * Registra un jugador y realiza el bootstrap inicial:
   * crea sistema, siembra planetas/lunas/NPCs, crea nave inicial,
   * otorga un módulo de motor básico, y emite tokens.
   *
   * @param string $email Email del jugador
   * @param string $nickname Apodo del jugador
   * @param string $password Contraseña en claro (se hashea dentro)
   *
   * @return array{
   *   player: Player,
   *   system: System,
   *   ship: PlayerShip,
   *   tokens: array{access_token:string,expires_in:int,refresh_token:string}
   * }
   *
   * @throws \RuntimeException EMAIL_TAKEN | NICKNAME_TAKEN | BOOTSTRAP_FAILED | TOKEN_SERVICE_NOT_CONFIGURED
   */
	public function registerPlayer(string $email, string $nickname, string $password): array {
		$db = new ODB();

		try {
			// 1.- Comprobar datos
			if ($this->emailExists($email)) {
				throw new \RuntimeException('EMAIL_TAKEN');
			}
			if ($this->nicknameExists($nickname)) {
				throw new \RuntimeException('NICKNAME_TAKEN');
			}

			// 2.- Crear jugador
			$player = Player::create();
			$player->email         = $email;
			$player->password_hash = password_hash($password, PASSWORD_BCRYPT);
			$player->nickname      = $nickname;
			$player->credits       = 0.0;
			$player->save();

			// 3.- Crear sistema inicial
			$system = $this->createHomeSystemFor($player->id);

			// 4.- Semilla de planetas / lunas / recursos / comerciantes
			$this->seedPlanetsAndMerchants($system->id);

			// 5.- Persistir relación home + presencia
			$home = PlayerSystemHome::create();
			$home->id_player = $player->id;
			$home->id_system = $system->id;
			$home->save();

			$presence = PlayerPresence::create();
			$presence->id_player    = $player->id;
			$presence->id_system    = $system->id;
			$presence->last_seen_at = date('Y-m-d H:i:s');
			$presence->save();

			// 6.- Crear nave inicial
			$ship = $this->createShipFor($player->id);

			// 7.- Dar módulo de motor básico
			$this->giveStarterEngineModule($player->id, $ship->id);

			// 8.- Generar tokens
      $tokens = $this->issueTokensForPlayer($player->id);

			return [
				'player' => $player,
				'system' => $system,
				'ship'   => $ship,
        'tokens' => $tokens
			];
		}
		catch (\Throwable $e) {
			if (in_array($e->getMessage(), ['EMAIL_TAKEN', 'NICKNAME_TAKEN'], true)) {
				throw $e;
			}
			throw new \RuntimeException('BOOTSTRAP_FAILED', 0, $e);
		}
	}

	/**
	 * Función para comprobar si un email está registrado
	 *
	 * @param string $email Email a comprobar
	 *
	 * @return bool Devuelve true si el email ya está registrado o false si no lo está
	 */
	private function emailExists(string $email): bool {
		return ($this->getPlayerByEmail($email) !== null);
	}

	/**
	 * Función para comprobar si un nickname está registrado
	 *
	 * @param string $nickname Nickname a comprobar
	 *
	 * @return bool Devuelve true si el nickname ya está registrado o false si no lo está
	 */
	private function nicknameExists(string $nickname): bool {
		return ($this->getPlayerByNickname($nickname) !== null);
	}

	/**
	 * Función para crear un nuevo sistema para un jugador
	 *
	 * @param int $id_player Id del jugador
	 *
	 * @return System Devuelve sistema creado
	 */
	private function createHomeSystemFor(int $id_player): System {
		$id_system_type = $this->getIdByCode('system_type', 'NORMAL');
		$id_star_class  = $this->getIdByCode('star_class',  'MAIN_SEQUENCE');

		$system = System::create();
		$system->id_system_type       = $id_system_type;
		$system->original_name        = $this->generateSystemName();
		$system->custom_name          = null;
		$system->id_player_renamed_by = null;
		$system->id_star_class        = $id_star_class;
		$system->seed                 = random_int(PHP_INT_MIN, PHP_INT_MAX);
		$system->save();

		return $system;
	}

	/**
   * Siembra planetas, lunas, recursos y comerciantes para un sistema.
   * La generación es simple y pseudo-determinista basada en id_system.
   *
   * @param int $id_system Id del sistema a sembrar
   * @return void
   */
	private function seedPlanetsAndMerchants(int $id_system): void {
		// Semilla reproducible a partir de id_system
		mt_srand($id_system);

		$id_body_rocky   = $this->getIdByCode('body_type', 'ROCKY');
		$id_body_oceanic = $this->getIdByCode('body_type', 'OCEANIC');
		$id_body_gaseous = $this->getIdByCode('body_type', 'GASEOUS');
		$id_body_hab     = $this->getIdByCode('body_type', 'HABITABLE');
		$id_body_lava    = $this->getIdByCode('body_type', 'LAVA');

		$resource_codes = ['GOLD', 'NICKEL', 'IRON', 'COPPER', 'TITANIUM', 'URANIUM'];
		$id_resources   = [];

		foreach ($resource_codes as $code) {
			$id_resources[$code] = $this->getIdByCode('resource', $code);
		}

		$id_lt_planet = $this->getIdByCode('location_type', 'PLANET');
		$id_lt_moon   = $this->getIdByCode('location_type', 'MOON');

		$planets_count = mt_rand(3, 6);
		$planet_ids    = [];

		for ($i = 1; $i <= $planets_count; $i++) {
			$body_type_pool = [
				$id_body_rocky,
				$id_body_rocky,
				$id_body_oceanic,
				$id_body_gaseous,
				$id_body_lava,
				$id_body_hab,
			];
			$id_body_type = $body_type_pool[array_rand($body_type_pool)];

			$p = Planet::create();
			$p->id_system                  = $id_system;
			$p->original_name              = "P-{$i}";
			$p->custom_name                = null;
			$p->id_player_renamed_by       = null;
			$p->id_body_type               = $id_body_type;
			$p->radius_km                  = mt_rand(2000, 70000);
			$p->distance_au                = round(0.2 + ($i * mt_rand(15, 45)/100), 4);
			$p->moons_count                = mt_rand(0, 3);
			$p->seed                       = random_int(1, PHP_INT_MAX);
			$p->is_discovered              = false;
			$p->id_player_first_discoverer = null;
			$p->save();

			$planet_ids[] = $p->id;

			// Recursos del planeta
			$res_sample = $this->sampleAssoc(id_resources, mt_rand(1, 3));
			foreach ($res_sample as $code => $id_res) {
				$lr = LocationResource::create();
				$lr->id_location_type = $id_lt_planet;
				$lr->id_location      = $p->id;
				$lr->id_resouce       = $id_res;
				$lr->max_capacity     = mt_rand(8000, 40000);
				$lr->current_capacity = $lr->max_capacity;
				$lr->last_depleted_at = null;
				$lr->save();
			}

			// Lunas
			for ($m = 1; $m <= $p->moons_count; $m++) {
        $body_type_moon = [$id_body_rocky, $id_body_rocky, $id_body_oceanic, $id_body_lava][array_rand([0,1,2,3])];
        $moon = Moon::create();
        $moon->id_planet            = $p->id;
        $moon->original_name        = "P-{$i}-M{$m}";
        $moon->custom_name          = null;
        $moon->id_player_renamed_by = null;
        $moon->id_body_type         = $body_type_moon;
        $moon->radius_km            = mt_rand(200, 3500);
        $moon->distance_km          = mt_rand(50_000, 600_000);
        $moon->seed                 = random_int(1, PHP_INT_MAX);
        $moon->is_discovered        = 0;
        $moon->id_player_first_discoverer = null;
        $moon->save();

        // Recursos de la luna (1–2 tipos)
        $res_sample_moon = $this->sampleAssoc($id_resources, mt_rand(1, 2));
        foreach ($res_sample_moon as $code => $id_res) {
          $lr = LocationResource::create();
          $lr->id_location_type = $id_lt_moon;
          $lr->id_location      = $moon->id;
          $lr->id_resource      = $id_res;
          $lr->max_capacity     = mt_rand(2_000, 12_000);
          $lr->current_capacity = $lr->max_capacity;
          $lr->last_depleted_at = null;
          $lr->save();
        }
      }
    }

    // Comerciantes: 1–2 merchants repartidos en cuerpos aleatorios (planetas)
    $merchants_count = mt_rand(1, 2);
    $id_race_human  = $this->getIdByCode('race', 'HUMAN');
    $id_mode_buy    = $this->getIdByCode('trade_mode', 'BUY');
    $id_mode_sell   = $this->getIdByCode('trade_mode', 'SELL');

    // Para módulos
    $id_mod_engine_basic = $this->getIdByCode('catalog_module', 'MOD_ENGINE_BASIC');
    $id_mod_shield_light = $this->getIdByCode('catalog_module', 'MOD_SHIELD_LIGHT');

    for ($k = 0; $k < $merchants_count; $k++) {
      $planet_id = $planet_ids[array_rand($planet_ids)];

      $m = Merchant::create();
      $m->id_race          = $id_race_human; // puedes aleatorizar la raza si quieres
      $m->id_location_type = $id_lt_planet;
      $m->id_location      = $planet_id;
      $m->seed             = random_int(1, PHP_INT_MAX);
      $m->save();

      // Stock de recursos: 2 entradas BUY + 2 SELL
      $res_buy  = array_values($this->sampleAssoc($id_resources, 2));
      $res_sell = array_values($this->sampleAssoc($id_resources, 2));

      foreach ($res_buy as $id_res) {
        $msr = MerchantStockResource::create();
        $msr->id_merchant   = $m->id;
        $msr->id_resource   = $id_res;
        $msr->id_trade_mode = $id_mode_buy;
        $msr->price         = $this->priceFromBase($id_res, 0.7, 1.0); // compra: algo menor al base
        $msr->qty           = mt_rand(1_000, 5_000);
        $msr->save();
      }

      foreach ($res_sell as $id_res) {
        $msr = MerchantStockResource::create();
        $msr->id_merchant   = $m->id;
        $msr->id_resource   = $id_res;
        $msr->id_trade_mode = $id_mode_sell;
        $msr->price         = $this->priceFromBase($id_res, 1.0, 1.5); // venta: algo mayor al base
        $msr->qty           = mt_rand(500, 2_000);
        $msr->save();
      }

      // Un merchant vende 2 módulos básicos
      $msm = MerchantStockModule::create();
      $msm->id_merchant = $m->id;
      $msm->id_module   = $id_mod_engine_basic;
      $msm->price       = $this->moduleBasePrice($id_mod_engine_basic, 0.9, 1.1);
      $msm->qty         = mt_rand(1, 4);
      $msm->save();

      $msm2 = MerchantStockModule::create();
      $msm2->id_merchant = $m->id;
      $msm2->id_module   = $id_mod_shield_light;
      $msm2->price       = $this->moduleBasePrice($id_mod_shield_light, 0.9, 1.1);
      $msm2->qty         = mt_rand(1, 3);
      $msm2->save();
		}
	}

	/**
   * Devuelve una muestra de pares (clave=>valor) aleatoria de un array asociativo.
   *
   * @param array<string,int> $assoc Array asociativo (ej: code=>id)
   * @param int $take Número de elementos a tomar
   * @return array<string,int> Subconjunto aleatorio
   */
	private function sampleAssoc(array $assoc, int $take): array {
    $keys = array_keys($assoc);
    shuffle($keys);
    $sel = array_slice($keys, 0, max(1, min($take, count($keys))));
    $out = [];
    foreach ($sel as $k) { $out[$k] = $assoc[$k]; }
    return $out;
  }

	/**
   * Calcula un precio a partir del base de un recurso según un multiplicador aleatorio.
   *
   * @param int $id_resource Id del recurso
   * @param float $min_mul Multiplicador mínimo (incl.)
   * @param float $max_mul Multiplicador máximo (incl.)
   * @return float Precio resultante (redondeado a 2 decimales)
   */
	private function priceFromBase(int $id_resource, float $min_mul, float $max_mul): float {
    $db = new ODB();
    $db->query('SELECT `base_value` FROM `resource` WHERE id = :id', ['id' => $id_resource]);
		$row = $db->next();
    $base = $row ? (float)$row['base_value'] : 1.0;
    $mul = mt_rand((int)($min_mul * 100), (int)($max_mul * 100)) / 100.0;
    return round(max(0.01, $base * $mul), 2);
  }

	/**
   * Calcula un precio de módulo a partir del precio base con un multiplicador aleatorio.
   *
   * @param int $id_module Id del módulo en catálogo
   * @param float $min_mul Multiplicador mínimo (incl.)
   * @param float $max_mul Multiplicador máximo (incl.)
   * @return float Precio resultante (redondeado a 2 decimales)
   */
  private function moduleBasePrice(int $id_module, float $min_mul, float $max_mul): float {
    $db = new ODB();
    $db->query('SELECT `price` FROM `catalog_module` WHERE `id` = :id', ['id' => $id_module]);
		$row = $db->next();
    $base = $row ? (float)$row['price'] : 100.0;
    $mul = mt_rand((int)($min_mul * 100), (int)($max_mul * 100)) / 100.0;
    return round(max(1.0, $base * $mul), 2);
  }

	/**
	 * Función para crear una nueva nave para un jugador
	 *
	 * @param int $id_player Id del jugador
	 *
	 * @return PlayerShip Devuelve nave creado
	 */
	private function createShipFor(int $id_player): PlayerShip {
		$id_ship_type = $this->getIdByCode('ship_type', 'SHIP_FIGHTER');
		[$hp, $res]   = $this->getShipBaseStats($id_ship_type);

		$ship = PlayerShip::create();
		$ship->id_player          = $id_player;
		$ship->id_ship_type       = $id_ship_type;
		$ship->name               = null;
		$ship->hp_current         = $hp;
		$ship->resistance_current = $res;
		$ship->cargo_used_kg      = 0;
		$ship->save();

		return $ship;
	}

	private function giveStarterEngineModule(int $id_player, int $id_player_ship): void {
    // Añadir al inventario y, si hay hueco, instalarlo
    $id_mod_engine_basic = $this->getIdByCode('catalog_module', 'MOD_ENGINE_BASIC');

    // Inventario jugador: upsert simple
    $db = new ODB();
    $db->query(
      'SELECT `id`, `qty` FROM `player_module` WHERE `id_player` = :p AND `id_module` = :m',
      ['p' => $id_player, 'm' => $id_mod_engine_basic]
    );
		$exists = $db->next();

    if ($exists === null) {
      $db->query(
        'INSERT INTO `player_module` (`id_player`, `id_module`, `qty`, `created_at`) VALUES (:p, :m, 1, NOW())',
        ['p' => $id_player, 'm' => $id_mod_engine_basic]
      );
    }
    else {
      $db->query(
        'UPDATE `player_module` SET `qty` = `qty` + 1, `updated_at` = NOW() WHERE `id` = :id',
        ['id' => $exists['id']]
      );
    }

    // Intentar instalar si hay hueco (comparando slots de nave vs módulos instalados)
    $db->query(
      'SELECT st.`module_slots` AS `max_slots`
         FROM `player_ship` ps
         JOIN `ship_type` st ON st.`id` = ps.`id_ship_type`
        WHERE ps.`id` = :id',
      ['id' => $id_player_ship]
    );
		$row_slots = $db->next();
    $db->query(
      'SELECT COUNT(*) AS c FROM `player_ship_module` WHERE `id_player_ship` = :id',
      ['id' => $id_player_ship]
    );
		$row_installed = $db->next();

    $max_slots = (int)($row_slots['max_slots'] ?? 0);
    $installed = (int)($row_installed['c'] ?? 0);

    if ($installed < $max_slots) {
      // Instalar
      $db->query(
        'INSERT INTO `player_ship_module` (`id_player_ship`, `id_module`, `installed_at`, `created_at`)
         VALUES (:p, :m, NOW(), NOW())',
        ['p' => $id_player_ship, 'm' => $id_mod_engine_basic]
      );
      // Consumir del inventario (si existía)
      $db->query(
        'UPDATE `player_module` SET `qty` = GREATEST(0, `qty` - 1), `updated_at` = NOW()
          WHERE `id_player` = ? AND `id_module` = ?',
        [$id_player, $id_mod_engine_basic]
      );
    }
  }

	/**
   * Obtiene el id de una fila de catálogo por su code.
   *
   * @param string $table Nombre de la tabla (catalog)
   * @param string $code Code a buscar
   * @return int Id encontrado
   *
   * @throws \RuntimeException CATALOG_CODE_NOT_FOUND:{$table}:{$code}
   */
	private function getIdByCode(string $table, string $code): int {
		$db = new ODB();
		$sql = "SELECT `id` FROM `{$table}` WHERE `code` = ?";
		$db->query($sql, [$code]);

		if ($res = $db->next()) {
			return (int) $res['id'];
		}

		throw new \RuntimeException("CATALOG_CODE_NOT_FOUND:{$table}:{$code}");
	}

	/**
   * Devuelve estadísticas base (hp, resistencia) para un tipo de nave.
   *
   * @param int $id_ship_type Id del tipo de nave
   * @return array{0:int,1:int} [hp_base, resistance_base]
   *
   * @throws \RuntimeException SHIP_TYPE_NOT_FOUND
   */
	private function getShipBaseStats(int $id_ship_type): array {
		$db = new ODB();
		$sql = "SELECT `hp_base`, `resistance_base` FROM `ship_type` WHERE `id` = ?";
		$db->query($sql, [$id_ship_type]);

		if ($res = $db->next()) {
			return [
				(int) $row['hp_base'],
				(int) $row['resistance_base']
			];
		}

		throw new \RuntimeException('SHIP_TYPE_NOT_FOUND');
	}

	/**
	 * Función para generar un nombre aleatorio para un sistema (por ejemplo GX-###)
	 *
	 * @return string Nombre del sistema
	 */
	private function generateSystemName(): string {
		return sprintf('GX-%03d', random_int(1, 999));
	}

	/**
   * Emite tokens de acceso y refresco para un jugador.
   *
   * @param int $id_player Id del jugador
   * @return array{access_token:string,expires_in:int,refresh_token:string} Tokens emitidos
   *
   * @throws \RuntimeException TOKEN_SERVICE_NOT_CONFIGURED
   */
	private function issueTokensForPlayer(int $id_player): array {
    $access = $this->token_service->createAccessToken($id_player); // string JWT
    $expires_in = $this->token_service->getAccessTokenTtl();        // en segundos (por ej. 3600)

    // Refresh token persistido en BD
    $refresh = bin2hex(random_bytes(32));
    $art = AuthRefreshToken::create();
    $art->id_player  = $id_player;
    $art->token      = $refresh;
    $art->expires_at = date('Y-m-d H:i:s', time() + (60*60*24*30)); // 30 días
    $art->revoked    = 0;
    $art->save();

    return [
      'access_token'  => $access,
      'expires_in'    => $expires_in,
      'refresh_token' => $refresh
    ];
  }
}
