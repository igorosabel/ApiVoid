<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\OFW\Tools\OTools;
use OsumiFramework\OFW\Plugins\OToken;
use OsumiFramework\App\Model\Player;

#[OModuleAction(
	url: '/register',
	services: ['ship', 'system']
)]
class registerAction extends OAction {
	/**
	 * Función para registrar un nuevo jugador
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$name   = $req->getParamString('name');
		$email  = $req->getParamString('email');
		$pass   = $req->getParamString('pass');

		$id    = 'null';
		$token = '';

		if (is_null($name) || is_null($email) || is_null($pass)) {
			$status = 'error';
		}

		if ($status=='ok') {
			$p = new Player();
			if ($p->find(['name' => $name])) {
				$status = 'name';
			}
			else if ($p->find(['email' => $email])) {
				$status = 'email';
			}
			else {
				$common  = OTools::getCache('common');
				$credits = $common['credits'];

				$p->set('name',    $name);
				$p->set('pass',    password_hash($pass, PASSWORD_BCRYPT));
				$p->set('email',   $email);
				$p->set('credits', $credits);
				$p->save();

				// Creo una nueva nave Scout
				$ship = $this->ship_service->generateShip($p);
				$p->set('id_ship', $ship->get('id'));

				// Genero un sistema nuevo para él
				$system = $this->system_service->generateSystem($p);
				$p->set('id_system', $system->get('id'));
				$p->save();

				$id = $p->get('id');

				$tk = new OToken($this->getConfig()->getExtra('secret'));
				$tk->addParam('id',   $id);
				$tk->addParam('name', $name);
				$tk->addParam('exp', time() + (24 * 60 * 60));
				$token = $tk->getToken();
			}
		}
		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('token',  $token);
	}
}
