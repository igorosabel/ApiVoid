<?php declare(strict_types=1);

namespace OsumiFramework\App\Task;

use OsumiFramework\OFW\Core\OTask;
use OsumiFramework\App\Utils\Server;

class serverTask extends OTask {
	public function __toString() {
		return "server: Nueva tarea server";
	}

	function __construct() {
		global $core;
		require_once $core->config->getDir('app_utils').'server.php';
	}

	public function run(array $options=[]): void {
		$server = new Server();
		$server->run();
	}
}
