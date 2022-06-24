<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\System;
use OsumiFramework\App\Model\Planet;
use OsumiFramework\App\Model\Moon;

#[OModuleAction(
	url: '/edit-name',
	filters: ['login']
)]
class editNameAction extends OAction {
	/**
	 * Función para cambiar el nombre a un sistema, planeta o luna
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');
		$name   = $req->getParamInt('name');

		if (is_null($filter) || $filter['status'] != 'ok' || is_null($id) || is_null($type) || is_null($name)) {
			$status = 'error';
		}

		if ($status=='ok') {
			switch ($type){
				case 'system': {
					$obj = new System();
				}
				break;
				case 'planet': {
					$obj = new Planet();
				}
				break;
				case 'moon': {
					$obj = new Moon();
				}
				break;
			}
			$obj->find(['id' => $id]);
			$obj->set('name', $name);
			$obj->save();
		}

		$this->getTemplate()->add('status', $status);
	}
}
