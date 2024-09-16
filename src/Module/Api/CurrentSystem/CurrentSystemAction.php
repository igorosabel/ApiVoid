<?php declare(strict_types=1);

namespace Osumi\OsumiFramework\App\Module\Api\CurrentSystem;

use Osumi\OsumiFramework\Routing\OAction;
use Osumi\OsumiFramework\Web\ORequest;
use Osumi\OsumiFramework\App\Model\Player;
use Osumi\OsumiFramework\App\Model\System;
use Osumi\OsumiFramework\App\Model\Ship;
use Osumi\OsumiFramework\App\Component\Api\ShortMessages\ShortMessagesComponent;
use Osumi\OsumiFramework\App\Component\Api\Characters\CharactersComponent;

class CurrentSystemAction extends OAction {
  public string $status       = 'ok';
  public string $system       = '';
  public string $star         = '';
  public int    $num_planets  = 0;
  public int    $credits      = 0;
  public int    $max_strength = 0;
  public int    $strength     = 0;
  public ?ShortMessagesComponent $messages = null;
  public ?CharactersComponent $characters = null;

	/**
	 * Función para obtener los datos del sistema actual
	 *
	 * @param ORequest $req Request object with method, headers, parameters and filters used
	 * @return void
	 */
	public function run(ORequest $req):void {
		$filter = $req->getFilter('Login');
		if (is_null($filter) || $filter['status'] != 'ok') {
			$this->status = 'error';
		}

		$this->messages   = new ShortMessagesComponent(['messages' => []]);
		$this->characters = new CharactersComponent(['characters' => []]);

		if ($this->status == 'ok') {
			$p = new Player();
			$p->find(['id' => $filter['id']]);
			$s = new System();
			if ($s->find(['id' => $p->get('id_system')])) {
				$this->system      = $s->get('name');
				$this->star        = $s->get('type');
				$this->num_planets = $s->get('num_planets');
				$this->credits     = $p->get('credits');

				$ship = new Ship();
				$ship->find(['id' => $p->get('id_ship')]);
				$this->max_strength = $ship->get('max_strength');
				$this->strength     = $ship->get('strength');

				$this->messages->setValue('messages', $this->service['Message']->getUnreadMessages($p->get('id')));
				$this->characters->setValue('characters', $this->service['System']->getCharactersInSystem($p->get('id'), $s->get('id')));
			}
			else {
				$this->status = 'navigate';
			}
		}
	}
}
