<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetSellItems;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\Ship;
use Osumi\OsumiFramework\App\Component\Api\Ships\ShipsComponent;
use Osumi\OsumiFramework\App\Component\Api\Modules\ModulesComponent;
use Osumi\OsumiFramework\App\Component\Api\Resources\ResourcesComponent;

class GetSellItemsAction extends OAction {
  public string $status = 'ok';
  public ?ShipsComponent $ships = null;
  public ?ModulesComponent $modules = null;
  public ?ResourcesComponent $resources = null;

	/**
	 * Función para obtener los objetos que un jugador puede vender
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id_npc = $req->getParamInt('id');
		$filter = $req->getFilter('login');
		$this->ships     = new ShipsComponent(['ships' => []]);
		$this->modules   = new ModulesComponent(['modules' => []]);
		$this->resources = new ResourcesComponent(['resources' => []]);

		if (is_null($id_npc) || is_null($filter) || $filter['status'] != 'ok') {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$npc = new NPC();
			$npc->find(['id' => $id_npc]);
			$player = new Player();
			$player->find(['id' => $filter['id']]);
			$ship = new Ship();
			$ship->find(['id' => $player->get('id_ship')]);

			$this->ships->setValue('ships', $this->service['Ship']->getSellShips($player, $ship, $npc));
			$this->modules->setValue('modules', $this->service['Module']->getSellModules($player, $npc));
			$this->resources->setValue('resources', $this->service['Eesource']->getSellResources($ship, $npc));
		}
	}
}
