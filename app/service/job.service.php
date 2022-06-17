<?php declare(strict_types=1);

namespace OsumiFramework\App\Service;

use OsumiFramework\OFW\Core\OService;

class jobService extends OService {
	/**
	 * Load service tools
	 */
	function __construct() {
		$this->loadService();
	}
}