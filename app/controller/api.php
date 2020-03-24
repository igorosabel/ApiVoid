<?php
class api extends OController{
	public $ship_service     = null;
	public $system_service   = null;
	public $module_service   = null;
	public $npc_service      = null;
	public $message_service  = null;
	public $resource_service = null;
	public $job_service      = null;

	function __construct(){
		$this->ship_service     = new shipService($this);
		$this->system_service   = new systemService($this);
		$this->module_service   = new moduleService($this);
		$this->npc_service      = new npcService($this);
		$this->message_service  = new messageService($this);
		$this->resource_service = new resourceService($this);
		$this->job_service      = new jobService($this);
	}

	/*
	 * Función para registrar un nuevo jugador
	 */
	function register($req){
		$status = 'ok';
		$name   = OTools::getParam('name',  $req['params'], false);
		$email  = OTools::getParam('email', $req['params'], false);
		$pass   = OTools::getParam('pass',  $req['params'], false);

		$id    = 'null';
		$token = '';

		if ($name===false || $email===false || $pass===false){
			$status = 'error';
		}

		if ($status=='ok'){
			$p = new Player();
			if ($p->find(['name'=>$name])){
				$status = 'name';
			}
			else if ($p->find(['email'=>$email])){
				$status = 'email';
			}
			else{
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
				$tk->addParam('exp', mktime() + (24 * 60 * 60));
				$token = $tk->getToken();
			}
		}
		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('token',  $token);
	}

	/*
	 * Función para iniciar sesión
	 */
	function login($req){
		$status = 'ok';
		$name   = OTools::getParam('name', $req['params'], false);
		$pass   = OTools::getParam('pass', $req['params'], false);

		$id    = 'null';
		$token = '';

		if ($name===false || $pass===false){
			$status = 'error';
		}

		if ($status=='ok'){
			$p = new Player();
			if ($p->find(['name'=>$name])){
				if (password_verify($pass, $p->get('pass'))){
					$id = $p->get('id');

					$tk = new OToken($this->getConfig()->getExtra('secret'));
					$tk->addParam('id',   $id);
					$tk->addParam('name', $name);
					$tk->addParam('exp', mktime() + (24 * 60 * 60));
					$token = $tk->getToken();
				}
				else{
					$status = 'error';
				}
			}
			else{
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('id',     $id);
		$this->getTemplate()->add('name',   $name);
		$this->getTemplate()->add('token',  $token);
	}

	/*
	 * Función para obtener los datos del sistema actual
	 */
	function currentSystem($req){
		$status = 'ok';
		if ($req['loginFilter']['status']!='ok'){
			$status = 'error';
		}
		$system       = '';
		$star         = '';
		$num_planets  = 0;
		$credits      = 0;
		$max_strength = 0;
		$strength     = 0;
		$messages     = [];
		$characters   = [];

		if ($status=='ok'){
			$p = new Player();
			$p->find(['id'=>$req['loginFilter']['id']]);
			$s = new System();
			if ($s->find(['id'=>$p->get('id_system')])){
				$system      = $s->get('name');
				$star        = $s->get('type');
				$num_planets = $s->get('num_planets');
				$credits     = $p->get('credits');

				$ship = new Ship();
				$ship->find(['id'=>$p->get('id_ship')]);
				$max_strength = $ship->get('max_strength');
				$strength     = $ship->get('strength');

				$messages   = $this->message_service->getUnreadMessages($p->get('id'));
				$characters = $this->system_service->getCharactersInSystem($p->get('id'), $s->get('id'));
			}
			else{
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
		$this->getTemplate()->addPartial('messages',   'api/short_messages', ['messages'   => $messages,   'extra'=>'nourlencode']);
		$this->getTemplate()->addPartial('characters', 'api/characters',     ['characters' => $characters, 'extra'=>'nourlencode']);
	}

	/*
	 * Función para obtener los datos de la tienda de un NPC
	 */
	function NPCShop($req){
		$status = 'ok';
		$id     = OTools::getParam('id', $req['params'], false);
		$npc    = null;

		if ($id===false || $req['loginFilter']['status']!='ok'){
			$status = 'error';
		}

		if ($status=='ok'){
			$npc = new NPC();
			if (!$npc->find(['id'=>$id])){
				$status = 'error';
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addPartial('npc', 'api/npc', ['npc' => $npc, 'extra'=>'nourlencode']);
	}

	/*
	 * Función para comprar un item de la tienda de un NPC
	 */
	function buy($req){
		$status = 'ok';
		$id_npc = OTools::getParam('idNPC', $req['params'], false);
		$id     = OTools::getParam('id',    $req['params'], false);
		$type   = OTools::getParam('type',  $req['params'], false);
		$num    = OTools::getParam('num',   $req['params'], false);
		$info   = '';

		if ($req['loginFilter']['status']!='ok' || $id_npc===false || $id===false || $type===false || $num===false){
			$status = 'error';
		}

		if ($status=='ok'){
			$credits = 0;
			$player = new Player();
			$player->find(['id'=>$req['loginFilter']['id']]);
			$npc = new NPC();
			$npc->find(['id'=>$id_npc]);

			switch ($type){
				case 1: {
					$obj = new NPCShip();
					$obj->find(['id_npc'=>$id_npc, 'id_ship'=>$id]);

					// Cojo la nave del NPC
					$ship = new Ship();
					$ship->find(['id'=>$id]);

					// Precio de la nave
					$ship_credits = $ship->get('credits') * (1 + ($npc->get('margin')/100));

					// Creo una copia para el jugador (por número de unidades que ha comprado)
					for ($i=1; $i<=$num; $i++){
						$new_ship = new Ship();
						$new_ship->set('id_player',     $player->get('id'));
						$new_ship->set('id_npc',        null);
						$new_ship->set('original_name', $ship->get('original_name'));
						$new_ship->set('name',          $ship->get('name'));
						$new_ship->set('id_type',       $ship->get('id_type'));
						$new_ship->set('max_strength',  $ship->get('max_strength'));
						$new_ship->set('strength',      $ship->get('strength'));
						$new_ship->set('endurance',     $ship->get('endurance'));
						$new_ship->set('shield',        $ship->get('shield'));
						$new_ship->set('id_engine',     $ship->get('id_engine'));
						$new_ship->set('speed',         $ship->get('speed'));
						$new_ship->set('max_cargo',     $ship->get('max_cargo'));
						$new_ship->set('cargo',         $ship->get('cargo'));
						$new_ship->set('damage',        $ship->get('damage'));
						$new_ship->set('id_generator',  $ship->get('id_generator'));
						$new_ship->set('max_energy',    $ship->get('max_energy'));
						$new_ship->set('energy',        $ship->get('energy'));
						$new_ship->set('slots',         $ship->get('slots'));
						$new_ship->set('crew',          $ship->get('crew'));
						$new_ship->set('credits',       $ship_credits);
						$new_ship->save();
					}

					// Coste de la nave (por número de unidades que ha comprado)
					$credits = $ship_credits * $num;
				}
				break;
				case 2: {
					$obj = new NPCModule();
					$obj->find(['id_npc'=>$id_npc, 'id_module'=>$id]);

					// Cojo el módulo del NPC
					$module = new Module();
					$module->find(['id'=>$id]);

					// Precio del módulo
					$module_credits = $module->get('credits') * (1 + ($npc->get('margin')/100));

					// Creo una copia para el jugador (por número de unidades que ha comprado)
					for ($i=1; $i<=$num; $i++){
						$new_module = new Module();
						$new_module->set('id_player', $player->get('id'));
						$new_module->set('id_npc',    null);
						$new_module->set('id_ship',   null);
						$new_module->set('name',      $module->get('name'));
						$new_module->set('id_type',   $module->get('id_type'));
						$new_module->set('engine',    $module->get('engine'));
						$new_module->set('shield',    $module->get('shield'));
						$new_module->set('cargo',     $module->get('cargo'));
						$new_module->set('damage',    $module->get('damage'));
						$new_module->set('crew',      $module->get('crew'));
						$new_module->set('energy',    $module->get('energy'));
						$new_module->set('slots',     $module->get('slots'));
						$new_module->set('credits',   $module_credits);
						$new_module->save();
					}

					// Coste del módulo (por número de unidades que ha comprado)
					$credits = $module_credits * $num;
				}
				break;
				case 3: {
					$obj = new NPCResource();
					$obj->find(['id_npc'=>$id_npc, 'type'=>$id]);

					$resources = OTools::getCache('resource');
					$key = array_search($id, array_column($resources['resources'], 'id'));
					$resource = $resources['resources'][$key];

					$ship = new Ship();
					$ship->find(['id'=>$player->get('id_ship')]);
					// Compruebo si tiene espacio para almacenar el recurso en la nave
					if ($ship->get('cargo')<$num){
						$status = 'no-room';
						$info = $ship->get('cargo');
					}
					else{
						// Actualizo espacio restante en la nave
						$ship->set('cargo', $ship->get('cargo') -$num);
						$ship->save();

						$ship_resource = new ShipResource();
						// Compruebo si ya tiene ese recurso en la nave
						if ($ship_resource->find(['id_ship'=>$ship->get('id'), 'type'=>$resource['id']])){
							// Actualizo la cantidad del recurso que ya tiene en la nave
							$ship_resource->set('value', $ship_resource->get('value') +$num);
						}
						else{
							// Creo un nuevo recurso en la nave
							$ship_resource->set('id_ship', $ship->get('id'));
							$ship_resource->set('type', $resource['id']);
							$ship_resource->set('value', $num);
						}
						$ship_resource->save();

						// Coste del recurso (por número de unidades que ha comprado)
						$credits = ($resource['credits'] * (1 + ($npc->get('margin')/100))) * $num;
					}
				}
				break;
			}

			if ($status=='ok'){
				$obj->set('value', $obj->get('value') -$num);
				$obj->save();

				$player->set('credits', $player->get('credits') -$credits);
				$player->save();
			}
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->add('info',   $info);
	}

	/*
	 * Función para obtener los objetos que un jugador puede vender
	 */
	function getSellItems($req){
		$status    = 'ok';
		$id_npc    = OTools::getParam('id', $req['params'], false);
		$ships     = [];
		$modules   = [];
		$resources = [];

		if ($id_npc===false || $req['loginFilter']['status']!='ok'){
			$status = 'error';
		}

		if ($status=='ok'){
			$npc = new NPC();
			$npc->find(['id'=>$id_npc]);
			$player = new Player();
			$player->find(['id'=>$req['loginFilter']['id']]);
			$ship = new Ship();
			$ship->find(['id'=>$player->get('id_ship')]);

			$ships     = $this->ship_service->getSellShips($player, $ship, $npc);
			$modules   = $this->module_service->getSellModules($player, $npc);
			$resources = $this->resource_service->getSellResources($ship, $npc);
		}

		$this->getTemplate()->add('status', $status);
		$this->getTemplate()->addPartial('ships',     'api/ships',     ['ships'=>$ships,         'extra'=>'nourlencode']);
		$this->getTemplate()->addPartial('modules',   'api/modules',   ['modules'=>$modules,     'extra'=>'nourlencode']);
		$this->getTemplate()->addPartial('resources', 'api/resources', ['resources'=>$resources, 'extra'=>'nourlencode']);
	}

	/*
	 * Función para vender un item del jugador a un NPC
	 */
	function sell($req){
		$status = 'ok';
		$id_npc = OTools::getParam('idNPC', $req['params'], false);
		$id     = OTools::getParam('id',    $req['params'], false);
		$type   = OTools::getParam('type',  $req['params'], false);
		$num    = OTools::getParam('num',   $req['params'], false);

		if ($req['loginFilter']['status']!='ok' || $id_npc===false || $id===false || $type===false || $num===false){
			$status = 'error';
		}

		if ($status=='ok'){
			$credits = 0;
			$player = new Player();
			$player->find(['id'=>$req['loginFilter']['id']]);
			$npc = new NPC();
			$npc->find(['id'=>$id_npc]);

			switch ($type){
				case 1: {
					// Cojo la nave del jugador
					$ship = new Ship();
					$ship->find(['id'=>$id]);

					// Precio de la nave
					$ship_credits = $ship->get('credits') * (1 + ($npc->get('margin')/100));

					// Sumo al jugador el valor de la nave
					$player->set('credits', $player->get('credits') + $ship_credits);
					$player->save();

					// Borro la nave
					$ship->delete();
				}
				break;
				case 2: {
					// Cojo el módulo del jugador
					$module = new Module();
					$module->find(['id'=>$id]);

					// Precio del módulo
					$module_credits = $module->get('credits') * (1 + ($npc->get('margin')/100));

					// Sumo al jugador el valor del módulo
					$player->set('credits', $player->get('credits') + $module_credits);
					$player->save();

					// Borro la nave
					$module->delete();
				}
				break;
				case 3: {
					$resources = OTools::getCache('resource');
					$key = array_search($id, array_column($resources['resources'], 'id'));
					$resource = $resources['resources'][$key];

					$ship = new Ship();
					$ship->find(['id'=>$player->get('id_ship')]);

					// Libero espacio en la nave
					$ship->set('cargo', $ship->get('cargo') + $num);
					$ship->save();

					// Coste del recurso (por número de unidades que ha vendido)
					$credits = ($resource['credits'] * (1 + ($npc->get('margin')/100))) * $num;

					// Sumo al jugador el valor del recurso
					$player->set('credits', $player->get('credits') + $credits);
					$player->save();

					// Cojo los recursos de ese tipo que tiene en la nave el jugador
					$ship_resource = new ShipResource();
					$ship_resource->find(['id_ship'=>$ship->get('id'), 'type'=>$resource['id']]);

					// Resto la cantidad que ha vendido
					$ship_resource->set('value', $ship_resource->get('value') - $num);

					// Si la cantidad final es 0, borro el recurso de la nave
					if ($ship_resource->get('value')==0){
						$ship_resource->delete();
					}
					else{
						$ship_resource->save();
					}
				}
				break;
			}
		}

		$this->getTemplate()->add('status', $status);
	}

	/*
	 * Función para obtener la información de un sistema
	 */
	 function getSystemInfo($req){
	 	$status = 'ok';
	 	$system = null;
	 	$connections = [];
	 	$id_player = 'null';

	 	if ($req['loginFilter']['status']!='ok'){
	 		$status = 'error';
		}

		if ($status=='ok'){
			$player = new Player();
			$player->find(['id'=>$req['loginFilter']['id']]);
			$id_player = $player->get('id');
			$system = new System();
			$system->find(['id'=>$player->get('id_system')]);
			$connections = $system->getConnections();
		}

		$this->getTemplate()->add('status',    $status);
		$this->getTemplate()->add('id_player', $id_player);
		$this->getTemplate()->addPartial('system',      'api/system',      ['system' => $system, 'extra'=>'nourlencode']);
		$this->getTemplate()->addPartial('connections', 'api/connections', ['connections' => $connections, 'extra'=>'nourlencode']);
	}
 
	/*
	 * Función para cambiar el nombre a un sistema, planeta o luna
	 */
	function editName($req){
		$status = 'ok';
		$id     = OTools::getParam('id', $req['params'], false);
		$type   = OTools::getParam('type', $req['params'], false);
		$name   = OTools::getParam('name', $req['params'], false);

		if ($req['loginFilter']['status']!='ok' || $id===false || $type===false || $name===false){
			$status = 'error';
		}

		if ($status=='ok'){
			switch ($type){
				case 'system': {
					$obj = new System();
				}
				break;
				case 'planet': {
					$obj = new Planet();
				}
				break;
				case 'moon': {
					$obj = new Moon();
				}
				break;
			}
			$obj->find(['id'=>$id]);
			$obj->set('name', $name);
			$obj->save();
		}

		$this->getTemplate()->add('status',    $status);
	}

	/*
	 * Función para explorar un planeta o una luna
	 */
	function explore($req){
		$status = 'ok';
		$id     = OTools::getParam('id', $req['params'], false);
		$type   = OTools::getParam('type', $req['params'], false);
		
		if ($req['loginFilter']['status']!='ok' || $id===false || $type===false){
			$status = 'error';
		}
		
		if ($status=='ok'){
			$player = new Player();
			$player->find(['id'=>$req['loginFilter']['id']]);
		}
		
		$this->getTemplate()->add('status',    $status);
	}
}