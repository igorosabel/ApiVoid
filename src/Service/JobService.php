<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Service;

use Osumi\OsumiFramework\Core\OService;

class JobService extends OService {
	function __construct() {
		$this->loadService();
	}
}
