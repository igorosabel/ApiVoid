<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\NPC;
use OsumiFramework\App\Component\NpcComponent;

#[OModuleAction(
	url: '/npc-shop',
	filter: 'login',
	components: ['api/npc']
)]
class NPCShopAction extends OAction {
	/**
	 * Función para obtener los datos de la tienda de un NPC
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('login');
		$npc_component = new NpcComponent(['npc' => null]);

		if (is_null($id) || is_null($filter) || $filter['status']!='ok') {
			$status = 'error';
		}

		if ($status=='ok') {
			$npc = new NPC();
			if ($npc->find(['id' => $id])) {
				$npc_component->setValue('npc', $npc);
			}
			else {
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('npc',    $npc_component);
	}
}
