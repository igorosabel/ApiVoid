<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Login;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Model\Player;

class LoginAction extends OAction {
  public string       $status = 'ok';
  public string | int $id     = 'null';
  public string       $name   = 'null';
  public string       $token  = 'null';

	/**
	 * Función para iniciar sesión
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$this->name = $req->getParamString('name');
		$pass = $req->getParamString('pass');

		if (is_null($this->name) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$p = new Player();
			if ($p->find(['name' => $this->name])) {
				if (password_verify($pass, $p->get('pass'))) {
					$this->id = $p->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $this->id);
					$tk->addParam('name', $this->name);
					$tk->addParam('exp', time() + (24 * 60 * 60));
					$this->token = $tk->getToken();
				}
				else {
					$this->status = 'error';
				}
			}
			else {
				$this->status = 'error';
			}
		}
	}
}
