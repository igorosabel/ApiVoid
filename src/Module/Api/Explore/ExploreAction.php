<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Explore;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Player;

class ExploreAction extends OAction {
  public string $status = 'ok';

	/**
	 * Función para explorar un planeta o una luna
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');

		if (is_null($filter) || $filter['status'] != 'ok' || is_null($id) || is_null($type)) {
			$this->status = 'error';
		}

		if ($this->status=='ok') {
			$player = new Player();
			$player->find(['id' => $filter['id']]);
		}
	}
}
