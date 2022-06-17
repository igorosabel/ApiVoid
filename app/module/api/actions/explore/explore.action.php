<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Player;

#[OModuleAction(
	url: '/explore',
	filter: 'login'
)]
class exploreAction extends OAction {
	/**
	 * Función para explorar un planeta o una luna
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');

		if (is_null($filter) || $filter['status'] != 'ok' || is_null($id) || is_null($type)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$player = new Player();
			$player->find(['id' => $filter['id']]);
		}

		$this->getTemplate()->add('status', $status);
	}
}
