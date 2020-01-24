<?php
class api extends OController{
  public $ship_service   = null;
  public $system_service = null;
  public $module_service = null;
  public $npc_service    = null;

  function __construct(){
    $this->ship_service   = new shipService($this);
    $this->system_service = new systemService($this);
    $this->module_service = new moduleService($this);
    $this->npc_service    = new npcService($this);
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
}