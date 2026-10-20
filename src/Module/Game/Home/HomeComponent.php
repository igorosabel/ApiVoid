<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Game\Home;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\GameService;

/**
 * Devuelve el "dashboard" del sistema actual del jugador:
 * - system: info básica del sistema (nombre, clase estelar, contadores)
 * - players: jugadores presentes en el mismo sistema
 * - planets: lista de planetas con tipo y nº de lunas
 * - npcs: comerciantes (NPC) del sistema
 *
 * Variables públicas para el template:
 * - string $status
 * - int    $system_id
 * - string $system_custom_name  (JSON-encoded string o 'null')
 * - string $system_original_name
 * - string $system_star_class_code
 * - int    $system_planets_count
 * - int    $system_moons_total
 * - string $players  (JSON array)
 * - string $planets  (JSON array)
 * - string $npcs     (JSON array)
 */
class HomeComponent extends OComponent {
  private ?GameService $game_service = null;

  public string $status = 'error';
  public int $system_id = 0;
  public string $system_custom_name = 'null';
  public string $system_original_name = '';
  public string $system_star_class_code = '';
  public int $system_planets_count = 0;
  public int $system_moons_total = 0;

  public string $players = '[]';
  public string $planets = '[]';
  public string $npcs    = '[]';

  public function __construct() {
		parent::__construct();
		$this->game_service = inject(GameService::class);
	}

  /**
   * Ejecuta el endpoint GET /game/home
   *
   * @return void
   */
  public function run(ORequest $req): void {
    global $core;
    $filter = $req->getMiddleware('Login');

    try {
      if (is_null($filter) || !array_key_exists('sub', $filter)) {
        $core->setHttpStatus(401);
        return;
  		}
      $id_player = $filter['sub'];

      // 1) Sistema actual (presencia) o casa (home)
      $id_system = $this->game_service->getCurrentSystemId($id_player);
      if ($id_system === null) {
        $core->setHttpStatus(404);
        return;
      }

      // 2) Información del sistema
      $system = $this->game_service->getSystemSummary($id_system);
      if ($system === null) {
        $core->setHttpStatus(404);
        return;
      }
      $this->system_id               = $system['id'];
      $this->system_custom_name      = $system['custom_name_json']; // '"Nombre"' o 'null'
      $this->system_original_name    = $system['original_name'];
      $this->system_star_class_code  = $system['star_class_code'];
      $this->system_planets_count    = $system['planets_count'];
      $this->system_moons_total      = $system['moons_total'];

      // 3) Jugadores presentes en el sistema (excluye al propio jugador si quieres)
      $players = $this->game_service->getPlayersInSystem($id_system, $id_player);
      $this->players = json_encode($players, JSON_UNESCAPED_UNICODE);

      // 4) Planetas del sistema (con tipo y nº de lunas)
      $planets = $this->game_service->getPlanetsOfSystem($id_system, $id_player);
      $this->planets = json_encode($planets, JSON_UNESCAPED_UNICODE);

      // 5) NPCs del sistema
      $npcs = $this->game_service->getNpcsOfSystem($id_system, $id_player);
      $this->npcs = json_encode($npcs, JSON_UNESCAPED_UNICODE);

      // OK → renderiza template
      $this->status = 'ok';
    }
    catch (\Throwable $e) {
      var_dump($e);
      $core->setHttpStatus(500);
    }
  }
}
