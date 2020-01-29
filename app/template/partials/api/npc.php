<?php if (is_null($values['npc'])): ?>
  null
<?php else: ?>
<?php $npc = $values['npc']; ?>
  {
    "id": <?php echo $npc->get('id') ?>,
    "name": "<?php echo urlencode($npc->get('name')) ?>",
    "idRace": <?php echo $npc->get('id_race') ?>,
    "ships": [
<?php foreach ($npc->getShips() as $j => $ship): ?>
      {
        "ship": {
          "id": <?php echo $ship->getShip()->get('id') ?>,
        	"name": "<?php echo urlencode($ship->getShip()->get('name')) ?>",
        	"idType": <?php echo $ship->getShip()->get('id_type') ?>,
        	"maxStrength": <?php echo is_null($ship->getShip()->get('max_strength')) ? 'null' : $ship->getShip()->get('max_strength') ?>,
        	"strength": <?php echo is_null($ship->getShip()->get('strength')) ? 'null' : $ship->getShip()->get('strength') ?>,
        	"endurance": <?php echo is_null($ship->getShip()->get('endurance')) ? 'null' : $ship->getShip()->get('endurance') ?>,
        	"shield": <?php echo is_null($ship->getShip()->get('shield')) ? 'null' : $ship->getShip()->get('shield') ?>,
        	"idEngine": <?php echo is_null($ship->getShip()->get('id_engine')) ? 'null' : $ship->getShip()->get('id_engine') ?>,
        	"speed": <?php echo is_null($ship->getShip()->get('speed')) ? 'null' : $ship->getShip()->get('speed') ?>,
        	"maxCargo": <?php echo is_null($ship->getShip()->get('max_cargo')) ? 'null' : $ship->getShip()->get('max_cargo') ?>,
        	"cargo": <?php echo is_null($ship->getShip()->get('cargo')) ? 'null' : $ship->getShip()->get('cargo') ?>,
        	"damage": <?php echo is_null($ship->getShip()->get('damage')) ? 'null' : $ship->getShip()->get('damage') ?>,
        	"idGenerator": <?php echo is_null($ship->getShip()->get('id_generator')) ? 'null' : $ship->getShip()->get('id_generator') ?>,
        	"maxEnergy": <?php echo is_null($ship->getShip()->get('max_energy')) ? 'null' : $ship->getShip()->get('max_energy') ?>,
        	"energy": <?php echo is_null($ship->getShip()->get('energy')) ? 'null' : $ship->getShip()->get('energy') ?>,
        	"slots": <?php echo is_null($ship->getShip()->get('slots')) ? 'null' : $ship->getShip()->get('slots') ?>,
        	"crew": <?php echo is_null($ship->getShip()->get('crew')) ? 'null' : $ship->getShip()->get('crew') ?>,
        	"credits": <?php echo is_null($ship->getShip()->get('credits')) ? 'null' : $ship->getShip()->get('credits') ?>
        },
        "value": <?php echo $ship->get('value') ?>
      }<?php if ($j<count($npc->getShips())-1): ?>,<?php endif ?>
<?php endforeach ?>
    ],
    "modules": [
<?php foreach ($npc->getModules() as $j => $module): ?>
      {
        "module": {
          "id": <?php echo $module->getModule()->get('id') ?>,
        	"name": "<?php echo urlencode($module->getModule()->get('name')) ?>",
        	"idType": <?php echo is_null($module->getModule()->get('id_type')) ? 'null' : $module->getModule()->get('id_type') ?>,
        	"engine": <?php echo is_null($module->getModule()->get('engine')) ? 'null' : $module->getModule()->get('engine') ?>,
        	"shield": <?php echo is_null($module->getModule()->get('shield')) ? 'null' : $module->getModule()->get('shield') ?>,
        	"cargo": <?php echo is_null($module->getModule()->get('cargo')) ? 'null' : $module->getModule()->get('cargo') ?>,
        	"damage": <?php echo is_null($module->getModule()->get('damage')) ? 'null' : $module->getModule()->get('damage') ?>,
        	"crew": <?php echo is_null($module->getModule()->get('crew')) ? 'null' : $module->getModule()->get('crew') ?>,
        	"energy": <?php echo is_null($module->getModule()->get('energy')) ? 'null' : $module->getModule()->get('energy') ?>,
        	"slots": <?php echo is_null($module->getModule()->get('slots')) ? 'null' : $module->getModule()->get('slots') ?>,
        	"credits": <?php echo is_null($module->getModule()->get('credits')) ? 'null' : $module->getModule()->get('credits') ?>
        },
        "value": <?php echo $module->get('value') ?>
      }<?php if ($j<count($npc->getModules())-1): ?>,<?php endif ?>
<?php endforeach ?>
    ],
    "resources": [
<?php foreach ($npc->getResources() as $j => $resource): ?>
      {
        "resource": {
          "id": <?php echo $resource->getResource()['id'] ?>,
          "name": "<?php echo urlencode($resource->getResource()['name']) ?>",
          "minPrice": <?php echo $resource->getResource()['min_price'] ?>,
          "maxPrice": <?php echo $resource->getResource()['max_price'] ?>
        },
        "value": <?php echo $resource->get('value') ?>
      }<?php if ($j<count($npc->getResources())-1): ?>,<?php endif ?>
<?php endforeach ?>
    ]
  }
<?php endif ?>