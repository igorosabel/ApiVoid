<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Auth\Login;

use Osumi\OsumiFramework\Core\OComponent;
use Osumi\OsumiFramework\App\DTO\LoginDTO;
use Osumi\OsumiFramework\App\Service\AuthService;

class LoginComponent extends OComponent {
  private ?AuthService $auth_service = null;

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
    $this->auth_service = inject(AuthService::class);
  }

  /**
   * Endpoint de login: valida credenciales y emite tokens.
   *
   * @param LoginDTO $data DTO con email y password
   * @return void
   */
  public function run(LoginDTO $data): void {
    global $core;

    if (!$data->isValid()) {
      $core->setHttpStatus(400);
			return;
    }

    try {
      $result = $this->auth_service->login(
        email:    trim((string) $data->email),
        password: (string) $data->password
      );

      $this->status               = 'ok';
			$this->player_id            = $result['player']['id'];
			$this->player_nickname      = '"'.$result['player']['nickname'].'"';
			$this->system_id            = $result['system']['id'];
			$this->system_original_name = '"'.$result['system']['original_name'].'"';
			$this->ship_id              = $result['ship']['id'];
			$this->ship_id_ship_type    = $result['ship']['id_ship_type'];
			$this->token_access_token   = '"'.$result['tokens']['access_token'].'"';
			$this->token_expires_in     = $result['tokens']['expires_in'];
			$this->token_refresh_token  = '"'.$result['tokens']['refresh_token'].'"';
    }
    catch (\Throwable $e) {
      $code = $e->getMessage();
      $http = match ($code) {
        'INVALID_CREDENTIALS' => 401,
        'TOKEN_SERVICE_NOT_CONFIGURED' => 500,
        default => 500
      };
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
