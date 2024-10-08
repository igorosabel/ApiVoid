<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\NPCResource;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Model\NPCModule;
use Osumi\OsumiFramework\App\Model\Module;
use Osumi\OsumiFramework\App\Model\NPCShip;
use Osumi\OsumiFramework\App\Model\Ship;
use Osumi\OsumiFramework\App\Service\ShipService;
use Osumi\OsumiFramework\App\Service\ModuleService;

class NpcService extends OService {
	public ?shipService   $ship_service   = null;
	public ?moduleService $module_service = null;

	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
		$this->ship_service   = new ShipService();
		$this->module_service = new ModuleService();
	}

	/**
	 * Obtiene una raza aleatoria
	 *
	 * @return int Id de la raza
	 */
	public function generateRace(): int {
		$race_list = OTools::getCache('race');
		$race_prob = [];
		foreach ($race_list['race_list'] as $race) {
			for ($i=1; $i<=$race['proportion']; $i++) {
				array_push($race_prob, $race['id']);
			}
		}

		$id_race = $race_prob[array_rand($race_prob)];

		return $id_race;
	}

	/**
	 * Crea un nuevo NPC y todos los artículos que pondrá a la venta
	 *
	 * @param System $system Sistema en el que estará el NPC
	 *
	 * @return NPC Nuevo NPC creado
	 */
	public function generateNPC(System $system): NPC {
		global $core;

		$common         = OTools::getCache('common');
		$npc_list       = OTools::getCache('npc');
		$hull_types     = OTools::getCache('hull');
		$resource_types = OTools::getCache('resource');

		$npc = new NPC();
		$npc_name = $npc_list['character_list'][array_rand($npc_list['character_list'])];
		$npc->set('name',$npc_name);

		$npc_race = $this->generateRace();
		$npc->set('id_race',    $npc_race);
		$npc->set('id_system',  $system->get('id'));
		$npc->set('margin',     rand($common['min_npc_margin'], $common['max_npc_margin']));
		$npc->set('last_reset', date('Y-m-d H:i:s', time()));
		$npc->save();

		// Hulls
		$num_hulls  = rand(0, $common['max_sell_hulls']);
		$hull_list  = [];
		if ($num_hulls>0) {
			while (count($hull_list)<$num_hulls) {
				$hull = $hull_types['hull_types'][array_rand($hull_types['hull_types'])];
				if (!in_array($hull['id'], $hull_list)) {
					array_push($hull_list, $hull['id']);
					$ship = $this->ship_service->generateShip(null, $npc, $hull['id']);

					$npc_ship = new NPCShip();
					$npc_ship->set('id_npc', $npc->get('id'));
					$npc_ship->set('id_ship', $ship->get('id'));
					$value  = rand(1, $common['max_sell_hulls']);
					$npc_ship->set('start_value', $value);
					$npc_ship->set('value', $value);
					$npc_ship->save();
				}
			}
		}

		// Modules
		$num_modules = rand(0, $common['max_sell_modules']);
		if ($num_modules>0) {
			for ($i=1; $i<=$num_modules; $i++) {
				$module = $this->module_service->generateModule(null, $npc);

				$npc_module = new NPCModule();
				$npc_module->set('id_npc', $npc->get('id'));
				$npc_module->set('id_module', $module->get('id'));
				$value  = rand(1, $common['max_sell_modules']);
				$npc_module->set('start_value', $value);
				$npc_module->set('value', $value);
				$npc_module->save();
			}
		}

		// Resources
		$num_resources  = rand(0, $ccommon['max_sell_resources']);
		$resource_list  = [];
		if ($num_resources>0) {
			while (count($resource_list)<$num_resources) {
				$resource = $resource_types['resource_types'][array_rand($resource_types['resource_types'])];
				if (!in_array($resource['id'], $resource_list)) {
					array_push($resource_list, $resource['id']);

					$npc_resource = new NPCResource();
					$npc_resource->set('id_npc', $npc->get('id'));
					$npc_resource->set('type', $resource['id']);
					$value = rand(1, $ccommon['max_sell_resources']);
					$npc_resource->set('start_value', $value);
					$npc_resource->set('value', $value);
					$npc_resource->save();
				}
			}
		}

		return $npc;
	}
}
