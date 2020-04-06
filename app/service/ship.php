<?php
class shipService extends OService{
  private $module_service;

  function __construct(){
    $this->loadService();
    $this->module_service = new moduleService();
  }

  public function generateShip($player = null, $npc = null, $id_hull = null){
    $common          = OTools::getCache('common');
    $hull_types      = OTools::getCache('hull');
    $shield_types    = OTools::getCache('shield');
    $engine_types    = OTools::getCache('engine');
    $generator_types = OTools::getCache('generator');

    if (is_null($id_hull)){
	    $id_hull = $common['default_ship_hull'];
    }

    $hull_type       = $hull_types['hull_types']['hull_'.$id_hull];
    $shield_type     = $shield_types['shield_types']['shield_'.$hull_type['id_shield']];
    $engine_type     = $engine_types['engine_types']['engine_'.$hull_type['id_engine']];
    $generator_type  = $generator_types['generator_types']['generator_'.$hull_type['id_generator']];

    // Primero creo la nave, sin armas ni módulos
    $ship = new Ship();
    if (!is_null($player)){
    	$ship->set('id_player', $player->get('id'));
    }
    if (!is_null($npc)){
	    $ship->set('id_npc', $npc->get('id'));
    }

    $ship_name = OTools::getRandomCharacters(['num'=>$common['system_name_chars'],'upper'=>true]).'-'.OTools::getRandomCharacters(['num'=>$common['system_name_nums'],'numbers'=>true]);
    $ship->set('original_name', $ship_name);
    $ship->set('name',          $ship_name);
    $ship->set('id_type',       $hull_type['id']);
    $ship->set('max_strength',  $hull_type['strength']);
    $ship->set('strength',      $hull_type['strength']);
    $ship->set('endurance',     $hull_type['endurance']);
    $ship->set('shield',        (!is_null($player)) ? $shield_type['strength'] : null);
    $ship->set('id_engine',     $hull_type['id_engine']);
    $ship->set('speed',         $engine_type['speed']);
    $ship->set('max_cargo',     $hull_type['cargo']);
    $ship->set('cargo',         $hull_type['cargo']);
    $ship->set('damage',        (!is_null($player)) ? $hull_type['damage'] : null);
    $ship->set('id_generator',  $hull_type['id_generator']);
    $ship->set('max_energy',    $generator_type['energy']);
    $ship->set('energy',        $generator_type['energy']);
    $ship->set('slots',         $hull_type['slots']);
    $ship->set('crew',          $hull_type['crew']);
    $ship->set('credits',       $hull_type['credits']);
    $ship->save();

    // Módulos de la nave
    if (!is_null($player)){
	    $shield = $this->module_service->generateModule($player, null, $ship, Module::SHIELD, $hull_type['id_shield']);
	    $gun    = $this->module_service->generateModule($player, null, $ship, Module::GUN,    $hull_type['id_gun']);
    }

    return $ship;
  }

  public function getSellShips($player, $ship, $npc){
    $db = new ODB();
    $sql = "SELECT * FROM `ship` WHERE `id_player` = ? AND `id` != ?";
	  $db->query($sql, [$player->get('id'), $ship->get('id')]);
	  $ret = [];

    while ($res = $db->next()){
      $ship = new Ship();
      $ship->update($res);

      $credits = $ship->get('credits') * (1 + ($npc->get('margin')/100));
      $ship->set('credits', $credits);

      array_push($ret, $ship);
    }

	  return $ret;
  }
}