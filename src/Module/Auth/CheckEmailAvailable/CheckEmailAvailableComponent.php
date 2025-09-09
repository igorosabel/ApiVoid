<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Auth\CheckEmailAvailable;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Service\PlayerService;

class CheckEmailAvailableComponent extends OComponent {
	private ?PlayerService $ps = null;

	public string $available = 'false';

	public function __construct() {
		parent::__construct();

		$this->ps = inject(PlayerService::class);
	}

	/**
	 * Function used to if an email is available
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req): void {
		$email = $req->getParamString('email');

		if (!is_null($email)) {
			$p = $this->ps->getPlayerByEmail($req->getParamString('email'));

			if (is_null($p)) {
				$this->available = 'true';
			}
		}
	}
}
