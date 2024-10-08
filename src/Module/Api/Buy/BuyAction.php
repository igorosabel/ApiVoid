<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Buy;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Model\NPCShip;
use Osumi\OsumiFramework\App\Model\Ship;
use Osumi\OsumiFramework\App\Model\NPCModule;
use Osumi\OsumiFramework\App\Model\Module;
use Osumi\OsumiFramework\App\Model\NPCResource;
use Osumi\OsumiFramework\App\Model\ShipResource;

class BuyAction extends OAction {
  public string $status = 'ok';
  public string | int $info = '';

	/**
	 * Función para comprar un item de la tienda de un NPC
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id_npc = $req->getParamInt('idNPC');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');
		$num    = $req->getParamInt('num');
		$filter = $req->getFilter('Login');

		if (is_null($filter) || $filter['status'] !== 'ok' || is_null($id_npc) || is_null($id) || is_null($type) || is_null($num)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$credits = 0;
			$player = new Player();
			$player->find(['id' => $filter['id']]);
			$npc = new NPC();
			$npc->find(['id' => $id_npc]);

			switch ($type) {
				case 1: {
					$obj = new NPCShip();
					$obj->find(['id_npc' => $id_npc, 'id_ship' => $id]);

					// Cojo la nave del NPC
					$ship = new Ship();
					$ship->find(['id' => $id]);

					// Precio de la nave
					$ship_credits = $ship->get('credits') * (1 + ($npc->get('margin') / 100));

					// Creo una copia para el jugador (por número de unidades que ha comprado)
					for ($i = 1; $i <= $num; $i++) {
						$new_ship = new Ship();
						$new_ship->set('id_player',     $player->get('id'));
						$new_ship->set('id_npc',        null);
						$new_ship->set('original_name', $ship->get('original_name'));
						$new_ship->set('name',          $ship->get('name'));
						$new_ship->set('id_type',       $ship->get('id_type'));
						$new_ship->set('max_strength',  $ship->get('max_strength'));
						$new_ship->set('strength',      $ship->get('strength'));
						$new_ship->set('endurance',     $ship->get('endurance'));
						$new_ship->set('shield',        $ship->get('shield'));
						$new_ship->set('id_engine',     $ship->get('id_engine'));
						$new_ship->set('speed',         $ship->get('speed'));
						$new_ship->set('max_cargo',     $ship->get('max_cargo'));
						$new_ship->set('cargo',         $ship->get('cargo'));
						$new_ship->set('damage',        $ship->get('damage'));
						$new_ship->set('id_generator',  $ship->get('id_generator'));
						$new_ship->set('max_energy',    $ship->get('max_energy'));
						$new_ship->set('energy',        $ship->get('energy'));
						$new_ship->set('slots',         $ship->get('slots'));
						$new_ship->set('crew',          $ship->get('crew'));
						$new_ship->set('credits',       $ship_credits);
						$new_ship->save();
					}

					// Coste de la nave (por número de unidades que ha comprado)
					$credits = $ship_credits * $num;
				}
				break;
				case 2: {
					$obj = new NPCModule();
					$obj->find(['id_npc' => $id_npc, 'id_module' => $id]);

					// Cojo el módulo del NPC
					$module = new Module();
					$module->find(['id' => $id]);

					// Precio del módulo
					$module_credits = $module->get('credits') * (1 + ($npc->get('margin') / 100));

					// Creo una copia para el jugador (por número de unidades que ha comprado)
					for ($i = 1; $i <= $num; $i++) {
						$new_module = new Module();
						$new_module->set('id_player', $player->get('id'));
						$new_module->set('id_npc',    null);
						$new_module->set('id_ship',   null);
						$new_module->set('name',      $module->get('name'));
						$new_module->set('id_type',   $module->get('id_type'));
						$new_module->set('engine',    $module->get('engine'));
						$new_module->set('shield',    $module->get('shield'));
						$new_module->set('cargo',     $module->get('cargo'));
						$new_module->set('damage',    $module->get('damage'));
						$new_module->set('crew',      $module->get('crew'));
						$new_module->set('energy',    $module->get('energy'));
						$new_module->set('slots',     $module->get('slots'));
						$new_module->set('credits',   $module_credits);
						$new_module->save();
					}

					// Coste del módulo (por número de unidades que ha comprado)
					$credits = $module_credits * $num;
				}
				break;
				case 3: {
					$obj = new NPCResource();
					$obj->find(['id_npc' => $id_npc, 'type' => $id]);

					$resources = OTools::getCache('resource');
					$key = array_search($id, array_column($resources['resources'], 'id'));
					$resource = $resources['resources'][$key];

					$ship = new Ship();
					$ship->find(['id' => $player->get('id_ship')]);

					// Compruebo si tiene espacio para almacenar el recurso en la nave
					if ($ship->get('cargo') < $num) {
						$this->status = 'no-room';
						$this->info = $ship->get('cargo');
					}
					else {
						// Actualizo espacio restante en la nave
						$ship->set('cargo', $ship->get('cargo') - $num);
						$ship->save();

						$ship_resource = new ShipResource();

						// Compruebo si ya tiene ese recurso en la nave
						if ($ship_resource->find(['id_ship' => $ship->get('id'), 'type' => $resource['id']])) {
							// Actualizo la cantidad del recurso que ya tiene en la nave
							$ship_resource->set('value', $ship_resource->get('value') + $num);
						}
						else {
							// Creo un nuevo recurso en la nave
							$ship_resource->set('id_ship', $ship->get('id'));
							$ship_resource->set('type', $resource['id']);
							$ship_resource->set('value', $num);
						}
						$ship_resource->save();

						// Coste del recurso (por número de unidades que ha comprado)
						$credits = ($resource['credits'] * (1 + ($npc->get('margin') / 100))) * $num;
					}
				}
				break;
			}

			if ($this->status === 'ok') {
				$obj->set('value', $obj->get('value') -$num);
				$obj->save();

				$player->set('credits', $player->get('credits') - $credits);
				$player->save();
			}
		}
	}
}
