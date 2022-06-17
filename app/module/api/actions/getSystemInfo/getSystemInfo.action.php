<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Player;
use OsumiFramework\App\Model\System;
use OsumiFramework\App\Component\SystemComponent;
use OsumiFramework\App\Component\ConnectionsComponent;

#[OModuleAction(
	url: '/get-system-info',
	filter: 'login',
	components: ['api/system', 'api/connections']
)]
class getSystemInfoAction extends OAction {
	/**
	 * Función para obtener la información de un sistema
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		$id_player = 'null';
		$system_component = new SystemComponent(['system' => null]);
		$connections_component = new ConnectionsComponent(['connections' => []]);


		if (is_null($filter) || $filter['status'] != 'ok') {
			$status = 'error';
		}

		if ($status=='ok') {
			$player = new Player();
			$player->find(['id' => $filter['id']]);
			$id_player = $player->get('id');
			$system = new System();
			$system->find(['id' => $player->get('id_system')]);

			$system_component->setValue('system', $system);
			$connections_component->setValue('connections', $system->getConnections());
		}

		$this->getTemplate()->add('status',      $status);
		$this->getTemplate()->add('id_player',   $id_player);
		$this->getTemplate()->add('system',      $system_component);
		$this->getTemplate()->add('connections', $connections_component);
	}
}
