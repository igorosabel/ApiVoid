<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\GetSystemInfo;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\System;
use Osumi\OsumiFramework\App\Component\Api\System\SystemComponent;
use Osumi\OsumiFramework\App\Component\Api\Connections\ConnectionsComponent;

class GetSystemInfoAction extends OAction {
  public string       $status    = 'ok';
  public string | int $id_player = 'null';
  public ?SystemComponent      $system      = null;
  public ?ConnectionsComponent $connections = null;

  public function __construct() {
    $this->system = new SystemComponent(['system' => null]);
		$this->connections = new ConnectionsComponent(['connections' => []]);
  }

	/**
	 * Función para obtener la información de un sistema
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');

		if (is_null($filter) || $filter['status'] !== 'ok') {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$player = new Player();
			$player->find(['id' => $filter['id']]);
			$this->id_player = $player->get('id');

			$s = new System();
			$s->find(['id' => $player->get('id_system')]);

			$this->system->setValue('system', $s);
			$this->connections->setValue('connections', $s->getConnections());
		}
	}
}
