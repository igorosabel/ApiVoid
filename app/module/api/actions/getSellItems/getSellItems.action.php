<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\NPC;
use OsumiFramework\App\Model\Player;
use OsumiFramework\App\Model\Ship;
use OsumiFramework\App\Component\ShipsComponent;
use OsumiFramework\App\Component\ModulesComponent;
use OsumiFramework\App\Component\ResourcesComponent;

#[OModuleAction(
	url: '/get-sell-items',
	filter: 'login',
	services: ['ship', 'module', 'resource'],
	components: ['api/ships', 'api/modules', 'api/resources']
)]
class getSellItemsAction extends OAction {
	/**
	 * Función para obtener los objetos que un jugador puede vender
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id_npc = $req->getParamInt('id');
		$filter = $req->getFilter('login');
		$ships_component     = new ShipsComponent(['ships' => []]);
		$modules_component   = new ModulesComponent(['modules' => []]);
		$resources_component = new ResourcesComponent(['resources' => []]);

		if (is_null($id_npc) || is_null($filter) || $filter['status'] != 'ok') {
			$status = 'error';
		}

		if ($status=='ok') {
			$npc = new NPC();
			$npc->find(['id' => $id_npc]);
			$player = new Player();
			$player->find(['id' => $filter['id']]);
			$ship = new Ship();
			$ship->find(['id' => $player->get('id_ship')]);

			$ships_component->setValue('ships', $this->ship_service->getSellShips($player, $ship, $npc));
			$modules_component->setValue('modules', $this->module_service->getSellModules($player, $npc));
			$resources_component->setValue('resources', $this->resource_service->getSellResources($ship, $npc));
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('ships',     $ships_component);
		$this->getTemplate()->add('modules',   $modules_component);
		$this->getTemplate()->add('resources', $resources_component);
	}
}
