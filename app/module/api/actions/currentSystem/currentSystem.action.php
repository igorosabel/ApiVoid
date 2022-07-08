<?php declare(strict_types=1);

namespace OsumiFramework\App\Module\Action;

use OsumiFramework\OFW\Routing\OModuleAction;
use OsumiFramework\OFW\Routing\OAction;
use OsumiFramework\OFW\Web\ORequest;
use OsumiFramework\App\Model\Player;
use OsumiFramework\App\Model\System;
use OsumiFramework\App\Model\Ship;
use OsumiFramework\App\Component\Api\ShortMessagesComponent;
use OsumiFramework\App\Component\Api\CharactersComponent;

#[OModuleAction(
	url: '/current-system',
	filters: ['login'],
	services: ['message', 'system']
)]
class currentSystemAction extends OAction {
	/**
	 * Función para obtener los datos del sistema actual
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$status = 'ok';
		$filter = $req->getFilter('login');
		if (is_null($filter) || $filter['status']!='ok') {
			$status = 'error';
		}
		$system       = '';
		$star         = '';
		$num_planets  = 0;
		$credits      = 0;
		$max_strength = 0;
		$strength     = 0;
		$short_messages_component = new ShortMessagesComponent(['messages' => []]);
		$characters_component = new CharactersComponent(['characters' => []]);

		if ($status=='ok') {
			$p = new Player();
			$p->find(['id' => $filter['id']]);
			$s = new System();
			if ($s->find(['id' => $p->get('id_system')])) {
				$system      = $s->get('name');
				$star        = $s->get('type');
				$num_planets = $s->get('num_planets');
				$credits     = $p->get('credits');

				$ship = new Ship();
				$ship->find(['id'=>$p->get('id_ship')]);
				$max_strength = $ship->get('max_strength');
				$strength     = $ship->get('strength');

				$short_messages_component->setValue('messages', $this->message_service->getUnreadMessages($p->get('id')));
				$characters_component->setValue('characters', $this->system_service->getCharactersInSystem($p->get('id'), $s->get('id')));
			}
			else {
				$status = 'navigate';
			}
		}

		$this->getTemplate()->add('status',       $status);
		$this->getTemplate()->add('system',       $system);
		$this->getTemplate()->add('star',         $star);
		$this->getTemplate()->add('num_planets',  $num_planets);
		$this->getTemplate()->add('credits',      $credits);
		$this->getTemplate()->add('max_strength', $max_strength);
		$this->getTemplate()->add('strength',     $strength);
		$this->getTemplate()->add('messages',     $short_messages_component);
		$this->getTemplate()->add('characters',   $characters_component);
	}
}
