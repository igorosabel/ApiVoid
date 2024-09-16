<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;
use Osumi\OsumiFramework\DB\ODB;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\Moon;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\Planet;
use Osumi\OsumiFramework\App\Model\System;
use Osumi\OsumiFramework\App\Model\Connection;
use Osumi\OsumiFramework\App\Model\Resource;
use Osumi\OsumiFramework\App\Service\NpcService;

class SystemService extends OService {
	private ?npcService $npc_service = null;

	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
		$this->npc_service = new NpcService();
	}

	/**
	 * Crea un nuevo sistema para el jugador indicado
	 *
	 * @param Player $player Jugador para el que crear el sistema
	 *
	 * @return System Nuevo sistema creado
	 */
	public function generateSystem(Player $player): System {
		global $core;

		$common            = OTools::getCache('common');
		$system_types      = OTools::getCache('system');
		$planet_types      = OTools::getCache('planet');
		$resource_types    = OTools::getCache('resource');

		$sun_type          = $system_types['mkk_types'][array_rand($system_types['mkk_types'])];
		$sun_spectral_type = $sun_type['spectral_types'][array_rand($sun_type['spectral_types'])];
		$sun_type_code     = $sun_type['type'].'-'.$system_types['spectral_types']['type_'.$sun_spectral_type]['type'];
		$sun_name          = OTools::getRandomCharacters(['num'=>$common['system_name_chars'],'upper'=>true]).'-'.OTools::getRandomCharacters(['num'=>$common['system_name_nums'],'numbers'=>true]);
		$num_planets       = rand($sun_type['min_planets'], $sun_type['max_planets']);
		$sun_radius        = rand($sun_type['min_radius'],  $sun_type['max_radius']);

		// NPCs en el sistema
		$npcs = 0;

		// Distancias a las que hay planetas, para que no choquen
		$planet_distances = [];

		// Primero creo el sistema
		$s = new System();
		$s->set('id_player',      $player->get('id'));
		$s->set('original_name',  $sun_name);
		$s->set('name',           $sun_name);
		$s->set('num_planets',    $num_planets);
		$s->set('fully_explored', ($num_planets==0));
		$s->set('num_npc',        $npcs);
		$s->set('type',           $sun_type_code);
		$s->set('radius',         $sun_radius);
		$s->save();

		//echo "SYSTEM\n";
		//echo "-------------------------------------------------------------------------------------\n";
		//echo "ID DISCOVERER: ".$player->get('id')."\n";
		//echo "ORIGINAL NAME: ".$sun_name."\n";
		//echo "NAME: ".$sun_name."\n";
		//echo "NUM PLANETS: ".$num_planets."\n";
		//echo "SUN TYPE: ".$sun_type_code."\n";
		//echo "SUN RADIUS: ".$sun_radius."\n";
		//echo "-------------------------------------------------------------------------------------\n\n";

		// Creo los planetas del sistema
		for ($i=1; $i<=$num_planets; $i++) {
			$p = new Planet();

			$planet_name = $sun_name.'-'.$i;

			$p->set('id_system',     $s->get('id'));
			$p->set('original_name', $planet_name);
			$p->set('name',          $planet_name);

			//echo "  PLANET\n";
			//echo "  -------------------------------------------------------------------------------------\n";
			//echo "  ORIGINAL NAME: ".$planet_name."\n";

			$ind                 = array_rand($sun_type['planet_types']);
			$planet_type_id      = $sun_type['planet_types'][$ind];
			$planet_type         = $planet_types['planet_types']['type_'.$planet_type_id];
			$planet_radius       = rand($planet_type['min_radius'], $planet_type['max_radius']);
			$planet_rotation     = rand(2, 100);
			$planet_explore_time = rand($common['min_time_explore'], $common['max_time_explore']);
			$num_moons           = rand($planet_type['min_moons'], $planet_type['max_moons']);

			//echo "  TYPE: ".$planet_type_id."\n";
			//echo "  RADIUS: ".$planet_radius."\n";
			//echo "  ROTATION: ".$planet_rotation."\n";
			//echo "  EXPLORE TIME: ".$planet_explore_time."\n";
			//echo "  NUM MOONS: ".$num_moons."\n";

			$p->set('type',         $planet_type_id);
			$p->set('radius',       $planet_radius);
			$p->set('rotation',     $planet_rotation);
			$p->set('num_moons',    $num_moons);
			$p->set('explored',     false);
			$p->set('explore_time', $planet_explore_time);

			$planet_distance = rand($planet_type['min_distance'], $planet_type['max_distance']);
			while (in_array($planet_distance, $planet_distances)) {
				$planet_distance = rand($planet_type['min_distance'], $planet_type['max_distance']);
			}
			array_push($planet_distances, $planet_distance);
			$p->set('distance', $planet_distance);

			//echo "  DISTANCE: ".$planet_distance."\n";
			//echo "  -------------------------------------------------------------------------------------\n\n";

			// NPC
			$planet_has_npc = false;
			if ($npcs<$common['max_npc']) {
				$npc_prob = rand(1, $common['npc_prob']);
				if ($npc_prob==1) {
					$npc = $this->npc_service->generateNPC($s);
					$p->set('id_npc', $npc->get('id'));
					$npcs++;
					$planet_has_npc = true;
				}
			}

			$planet_resource_list  = [];
			if (!$planet_has_npc) {
				// Resources
				$num_resources  = rand(0, $common['max_sell_resources']);
				if ($num_resources>0) {
					while (count($planet_resource_list) < $num_resources) {
						$resource = $resource_types['resources'][array_rand($resource_types['resources'])];
						if (!array_key_exists($resource['id'], $planet_resource_list)) {
							$planet_resource_list[$resource['id']] = rand($resource['min'], $resource['max']);
						}
					}
				}
			}

			$p->save();

			if (count($planet_resource_list)>0) {
				foreach ($planet_resource_list as $resource_id=>$value) {
					$planet_resource = new Resource();
					$planet_resource->set('id_planet', $p->get('id'));
					$planet_resource->set('id_moon',   null);
					$planet_resource->set('type',      $resource_id);
					$planet_resource->set('value',     $value);

					$planet_resource->save();
				}
			}

			$moon_distances = [];

			// Creo las lunas del planeta
			for ($j=1; $j<=$num_moons; $j++) {
				$m = new Moon();

				$moon_name = $planet_name.'-L'.$j;

				//echo "    MOON\n";
				//echo "    -------------------------------------------------------------------------------------\n";
				//echo "    ORIGINAL NAME: ".$moon_name."\n";

				$m->set('id_planet',     $p->get('id'));
				$m->set('original_name', $moon_name);
				$m->set('name',          $moon_name);
				$m->set('type',          rand(1,5));

				$moon_radius = rand(1000, floor($planet_radius*0.66));
				$m->set('radius',        $moon_radius);

				$moon_rotation = rand(2,100);
				$m->set('rotation',      $moon_rotation);

				//echo "    RADIUS: ".$moon_radius."\n";

				$moon_distance = rand(1, $num_moons);
				while (in_array($moon_distance, $moon_distances)) {
					$moon_distance = rand(1, $num_moons);
				}
				array_push($moon_distances, $moon_distance);
				$m->set('distance',     $moon_distance);
				//echo "    DISTANCE: ".$moon_distance."\n";

				$moon_explore_time = rand($common['min_time_explore'], $common['max_time_explore']);
				$m->set('explored',     false);
				$m->set('explore_time', $moon_explore_time);

				$moon_has_npc = false;
				if ($npcs<$common['max_npc']) {
					$npc_prob = rand(1,$core->config->getExtra('npc_prob'));
					if ($npc_prob==1) {
						$npc = $this->npc_service->generateNPC($s);
						$m->set('id_npc', $npc->get('id'));
						$npcs++;
						$moon_has_npc = true;
					}
				}

				$moon_resource_list  = [];
				if (!$moon_has_npc) {
					// Resources
					$resource_types = OTools::getCache('resource');
					$num_resources  = rand(0, $common['max_sell_resources']);
					if ($num_resources>0) {
						while (count($moon_resource_list) < $num_resources) {
							$resource = $resource_types['resources'][array_rand($resource_types['resources'])];
							if (!array_key_exists($resource['id'], $moon_resource_list)) {
								$moon_resource_list[$resource['id']] = rand($resource['min'], $resource['max']);
							}
						}
					}
				}

				//echo "    -------------------------------------------------------------------------------------\n\n";

				$m->save();

				if (count($moon_resource_list)>0) {
					foreach ($moon_resource_list as $resource_id=>$value) {
						$planet_resource = new Resource();
						$planet_resource->set('id_planet', null);
						$planet_resource->set('id_moon',   $m->get('id'));
						$planet_resource->set('type',      $resource_id);
						$planet_resource->set('value',     $value);

						$planet_resource->save();
					}
				}
			}
		}

		// Conexiones
		$num_connections = rand(1, $common['system_max_connections']);
		for ($i=1; $i<=$num_connections; $i++) {
			$conn = new Connection();
			$conn->set('id_system_start', $s->get('id'));
			$conn->set('id_system_end', null);
			$conn->set('order', $i);
			$conn->set('navigate_time', rand($common['min_time_explore'], $common['max_time_explore']));
			$conn->save();
		}

		return $s;
	}

	/**
	 * Obtiene la lista de jugadores y NPC de un sistema
	 *
	 * @param int $id_player Id del jugador para descartarlo del resto
	 *
	 * @param int $id_system Id del sistema
	 *
	 * @return array Lista de otros jugadores y NPCs del sistema indicado
	 */
	public function getCharactersInSystem(int $id_player, int $id_system): array {
		$characters = [];
		$db = new ODB();

		// Jugadores
		$sql = "SELECT * FROM `player` WHERE `id_system` = ? AND `id` != ?";
		$db->query($sql, [$id_system, $id_player]);
		while ($res = $db->next()) {
			$player = new Player();
			$player->update($res);
			$char = [
				'id' => $player->get('id'),
				'type' => 1,
				'name' => $player->get('name')
			];
			array_push($characters, $char);
		}

		// NPC
		$sql = "SELECT * FROM `npc` WHERE `id_system` = ? AND `found` = 1";
		$db->query($sql, [$id_system]);
		while ($res = $db->next()) {
			$npc = new NPC();
			$npc->update($res);
			$char = [
				'id' => $npc->get('id'),
				'type' => 2,
				'name' => $npc->get('name')
			];
			array_push($characters, $char);
		}

		return $characters;
	}
}
