<?php
class api extends OController{
  public $ship_service    = null;
  public $system_service  = null;
  public $module_service  = null;
  public $npc_service     = null;
  public $message_service = null;

  function __construct(){
    $this->ship_service    = new shipService($this);
    $this->system_service  = new systemService($this);
    $this->module_service  = new moduleService($this);
    $this->npc_service     = new npcService($this);
    $this->message_service = new messageService($this);
  }

  /*
   * Función para registrar un nuevo jugador
   */
  function register($req){
    $status = 'ok';
    $name   = Base::getParam('name',  $req['url_params'], false);
    $email  = Base::getParam('email', $req['url_params'], false);
    $pass   = Base::getParam('pass',  $req['url_params'], false);

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
        $common  = Base::getCache('common');
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

      $this->getTemplate()->add('status', $status);
      $this->getTemplate()->add('id',     $id);
      $this->getTemplate()->add('name',   $name);
      $this->getTemplate()->add('token',  $token);
    }
  }
  /*
   * Función para iniciar sesión
   */
  function login($req){
    $status = 'ok';
    $name   = Base::getParam('name', $req['url_params'], false);
    $pass   = Base::getParam('pass', $req['url_params'], false);

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
    if ($req['filter']['status']!='ok'){
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
      $p->find(['id'=>$req['filter']['id']]);
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
}