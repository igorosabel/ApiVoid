<?php
class moduleService extends OService{
	function __construct($controller=null){
		$this->setController($controller);
	}

	public function generateModule($player = null, $npc = null, $ship = null, $module_type = null, $id_type = null){
		$id_player   = (!is_null($player)) ? $player->get('id') : null;
		$id_npc      = (!is_null($npc)) ? $npc->get('id') : null;
		$id_ship     = (!is_null($ship)) ? $ship->get('id') : null;
		$module_type = (!is_null($module_type)) ? $module_type : rand(1, 6);

		$module = new Module();
	    $module->set('id_player', $id_player);
	    $module->set('id_npc',    $id_npc);
	    $module->set('id_ship',   $id_ship);

		switch ($module_type){
			case Module::ENGINE : {
				$engine_types = OTools::getCache('engine');
				$id_type      = (!is_null($id_type)) ? $id_type : rand(1, count($engine_types['engine_types']));
				$type         = $engine_types['engine_types']['engine_'.$id_type];
				$module->set('engine',  $type['multiplier']);
			}
			break;
			case Module::SHIELD : {
				$shield_types = OTools::getCache('shield');
				$id_type      = (!is_null($id_type)) ? $id_type : rand(1, count($shield_types['shield_types']));
				$type         = $shield_types['shield_types']['shield_'.$id_type];
				$module->set('shield',  $type['strength']);
			}
			break;
			case Module::CARGO : {
				$cargo_types = OTools::getCache('cargo');
				$id_type      = (!is_null($id_type)) ? $id_type : rand(1, count($cargo_types['cargo_types']));
				$type         = $cargo_types['cargo_types']['cargo_'.$id_type];
				$module->set('cargo',  $type['space']);
			}
			break;
			case Module::GUN : {
				$gun_types = OTools::getCache('gun');
				$id_type   = (!is_null($id_type)) ? $id_type : rand(1, count($gun_types['gun_types']));
				$type      = $gun_types['gun_types']['gun_'.$id_type];
				$module->set('damage',  $type['strength']);
			}
			break;
			case Module::CABIN : {
				$cabin_types = OTools::getCache('cabin');
				$id_type      = (!is_null($id_type)) ? $id_type : rand(1, count($cabin_types['cabin_types']));
				$type         = $cabin_types['cabin_types']['cabin_'.$id_type];
				$module->set('crew',  $type['space']);
			}
			break;
			case Module::ENERGY : {
				$generator_types = OTools::getCache('generator');
				$id_type      = (!is_null($id_type)) ? $id_type : rand(1, count($generator_types['generator_types']));
				$type         = $generator_types['generator_types']['generator_'.$id_type];
			}
			break;
		}


	    $module->set('name',      $type['name']);
	    $module->set('id_type',   $module_type);
	    $module->set('energy',    $type['energy']);
	    $module->set('slots',     $type['slots']);
	    $module->set('credits',   $type['credits']);
	    $module->save();

	    return $module;
	}

	public function getSellModules($player, $npc){
		$db = $this->getController()->getDb();
    $sql = "SELECT * FROM `module` WHERE `id_player` = ? AND `id_ship` IS NULL";
	  $db->query($sql, [$player->get('id')]);
	  $ret = [];

    while ($res = $db->next()){
      $module = new Module();
      $module->update($res);

			$credits = $module->get('credits') * (1 + ($npc->get('margin')/100));
	    $module->set('credits', $credits);

      array_push($ret, $module);
    }

	  return $ret;
	}
}