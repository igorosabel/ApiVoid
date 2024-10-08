<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\Register;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\Tools\OTools;
use Osumi\OsumiFramework\Plugins\OToken;
use Osumi\OsumiFramework\App\Service\ShipService;
use Osumi\OsumiFramework\App\Service\SystemService;
use Osumi\OsumiFramework\App\Model\Player;

class RegisterAction extends OAction {
  private ?ShipService $shs = null;
  private ?SystemService $ss = null;

  public string       $status = 'ok';
  public string | int $id     = 'null';
  public string       $name   = 'null';
  public string       $token  = 'null';

  public function __construct() {
    $this->shs = inject(ShipService::class);
    $this->ss = inject(SystemService::class);
  }

	/**
	 * Función para registrar un nuevo jugador
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$this->name = $req->getParamString('name');
		$email  = $req->getParamString('email');
		$pass   = $req->getParamString('pass');

		if (is_null($this->name) || is_null($email) || is_null($pass)) {
			$this->status = 'error';
		}

		if ($this->status === 'ok') {
			$p = new Player();
			if ($p->find(['name' => $this->name])) {
				$this->status = 'name';
			}
			else if ($p->find(['email' => $email])) {
				$this->status = 'email';
			}
			else {
				$common  = OTools::getCache('common');
				$credits = $common['credits'];

				$p->set('name',    $this->name);
				$p->set('pass',    password_hash($pass, PASSWORD_BCRYPT));
				$p->set('email',   $email);
				$p->set('credits', $credits);
				$p->save();

				// Creo una nueva nave Scout
				$ship = $this->shs->generateShip($p);
				$p->set('id_ship', $ship->get('id'));

				// Genero un sistema nuevo para él
				$system = $this->ss->generateSystem($p);
				$p->set('id_system', $system->get('id'));
				$p->save();

				$this->id = $p->get('id');

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id',   $this->id);
				$tk->addParam('name', $this->name);
				$tk->addParam('exp', time() + (24 * 60 * 60));
				$this->token = $tk->getToken();
			}
		}
	}
}
