<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Auth\Register;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\RegisterDTO;
use Osumi\OsumiFramework\App\Service\PlayerService;

class RegisterComponent extends OComponent {
	private ?PlayerService $player_service = null;

	public string       $status               = 'error';
	public string | int $player_id            = 'null';
	public string       $player_nickname      = 'null';
	public string | int $system_id            = 'null';
	public string       $system_original_name = 'null';
	public string | int $ship_id              = 'null';
	public string | int $ship_id_ship_type    = 'null';
	public string       $token_access_token   = 'null';
	public string | int $token_expires_in     = 'null';
	public string       $token_refresh_token  = 'null';

	public function __construct() {
		parent::__construct();

		$this->player_service = inject(PlayerService::class);
	}

	/**
	 * Function used to if an email is available
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(RegisterDTO $data): void {
		global $core;

		if (!$data->isValid()) {
			$core->setHttpStatus(400);
			return;
		}

		if ($data->acceptTerms !== true) {
			$this->status = 'error-terms';
			$core->setHttpStatus(400);
			return;
		}

		try {
			$result = $this->player_service->registerPlayer(
				email: trim(urldecode($data->email)),
				nickname: trim(urldecode($data->nickname)),
				password: urldecode($data->password)
			);

			$core->setHttpStatus(201);
			$this->status               = 'ok';
			$this->player_id            = $result['player']->id;
			$this->player_nickname      = '"'.$result['player']->nickname.'"';
			$this->system_id            = $result['system']->id;
			$this->system_original_name = '"'.$result['system']->original_name.'"';
			$this->ship_id              = $result['ship']->id;
			$this->ship_id_ship_type    = $result['ship']->id_ship_type;
			$this->token_access_token   = '"'.$result['tokens']['access_token'].'"';
			$this->token_expires_in     = $result['tokens']['expires_in'];
			$this->token_refresh_token  = '"'.$result['tokens']['refresh_token'].'"';
		}
		catch (\Throwable $e) {
			$code = $e->getMessage();
			$http = ($code === 'EMAIL_TAKEN' || $code === 'NICKNAME_TAKEN') ? 409 : 500;
			$core->setHttpStatus($http);
			$this->status               = 'error';
			$this->player_id            = 'null';
			$this->player_nickname      = 'null';
			$this->system_id            = 'null';
			$this->system_original_name = 'null';
			$this->ship_id              = 'null';
			$this->ship_id_ship_type    = 'null';
			$this->token_access_token   = 'null';
			$this->token_expires_in     = 'null';
			$this->token_refresh_token  = 'null';
		}
	}
}
