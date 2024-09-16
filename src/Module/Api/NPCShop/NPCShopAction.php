<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\NPCShop;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\NPC;
use Osumi\OsumiFramework\App\Component\Api\Npc\NpcComponent;

class NPCShopAction extends OAction {
  public string $status = 'ok';
  public ?NpcComponent $npc = null;

	/**
	 * Función para obtener los datos de la tienda de un NPC
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$id     = $req->getParamInt('id');
		$filter = $req->getFilter('Login');
		$this->npc = new NpcComponent(['npc' => null]);

		if (is_null($id) || is_null($filter) || $filter['status']!='ok') {
			$this->status = 'error';
		}

		if ($this->status == 'ok') {
			$n = new NPC();
			if ($n->find(['id' => $id])) {
				$this->npc->setValue('npc', $n);
			}
			else {
				$this->status = 'error';
			}
		}

	}
}
