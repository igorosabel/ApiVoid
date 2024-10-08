<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Sell;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Model\Ship;
use Osumi\OsumiFramework\App\Model\Module;
use Osumi\OsumiFramework\App\Model\ShipResource;

class SellAction extends OAction {
  public string $status = 'ok';

	/**
	 * Función para vender un item del jugador a un NPC
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		$id_npc = $req->getParamInt('idNPC');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');
		$num    = $req->getParamInt('num');

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
					// Cojo la nave del jugador
					$ship = new Ship();
					$ship->find(['id' => $id]);

					// Precio de la nave
					$ship_credits = $ship->get('credits') * (1 + ($npc->get('margin') / 100));

					// Sumo al jugador el valor de la nave
					$player->set('credits', $player->get('credits') + $ship_credits);
					$player->save();

					// Borro la nave
					$ship->delete();
				}
				break;
				case 2: {
					// Cojo el módulo del jugador
					$module = new Module();
					$module->find(['id' => $id]);

					// Precio del módulo
					$module_credits = $module->get('credits') * (1 + ($npc->get('margin') / 100));

					// Sumo al jugador el valor del módulo
					$player->set('credits', $player->get('credits') + $module_credits);
					$player->save();

					// Borro la nave
					$module->delete();
				}
				break;
				case 3: {
					$resources = OTools::getCache('resource');
					$key = array_search($id, array_column($resources['resources'], 'id'));
					$resource = $resources['resources'][$key];

					$ship = new Ship();
					$ship->find(['id' => $player->get('id_ship')]);

					// Libero espacio en la nave
					$ship->set('cargo', $ship->get('cargo') + $num);
					$ship->save();

					// Coste del recurso (por número de unidades que ha vendido)
					$credits = ($resource['credits'] * (1 + ($npc->get('margin') / 100))) * $num;

					// Sumo al jugador el valor del recurso
					$player->set('credits', $player->get('credits') + $credits);
					$player->save();

					// Cojo los recursos de ese tipo que tiene en la nave el jugador
					$ship_resource = new ShipResource();
					$ship_resource->find(['id_ship' => $ship->get('id'), 'type' => $resource['id']]);

					// Resto la cantidad que ha vendido
					$ship_resource->set('value', $ship_resource->get('value') - $num);

					// Si la cantidad final es 0, borro el recurso de la nave
					if ($ship_resource->get('value') === 0) {
						$ship_resource->delete();
					}
					else {
						$ship_resource->save();
					}
				}
				break;
			}
		}
	}
}
