<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\EditName;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\System;
use Osumi\OsumiFramework\App\Model\Planet;
use Osumi\OsumiFramework\App\Model\Moon;

class EditNameAction extends OAction {
  public string $status = 'ok';

	/**
	 * Función para cambiar el nombre a un sistema, planeta o luna
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		$id     = $req->getParamInt('id');
		$type   = $req->getParamInt('type');
		$name   = $req->getParamInt('name');

		if (is_null($filter) || $filter['status'] !== 'ok' || is_null($id) || is_null($type) || is_null($name)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
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
	}
}
