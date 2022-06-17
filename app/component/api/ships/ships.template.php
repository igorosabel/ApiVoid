<?php foreach ($values['ships'] as $i=>$ship): ?>
	{
		"id": <?php echo $ship->get('id') ?>,
		"name": "<?php echo urlencode($ship->get('name')) ?>",
		"idType": <?php echo $ship->get('id_type') ?>,
		"maxStrength": <?php echo is_null($ship->get('max_strength')) ? 'null' : $ship->get('max_strength') ?>,
		"strength": <?php echo is_null($ship->get('strength')) ? 'null' : $ship->get('strength') ?>,
		"endurance": <?php echo is_null($ship->get('endurance')) ? 'null' : $ship->get('endurance') ?>,
		"shield": <?php echo is_null($ship->get('shield')) ? 'null' : $ship->get('shield') ?>,
		"idEngine": <?php echo is_null($ship->get('id_engine')) ? 'null' : $ship->get('id_engine') ?>,
		"speed": <?php echo is_null($ship->get('speed')) ? 'null' : $ship->get('speed') ?>,
		"maxCargo": <?php echo is_null($ship->get('max_cargo')) ? 'null' : $ship->get('max_cargo') ?>,
		"cargo": <?php echo is_null($ship->get('cargo')) ? 'null' : $ship->get('cargo') ?>,
		"damage": <?php echo is_null($ship->get('damage')) ? 'null' : $ship->get('damage') ?>,
		"idGenerator": <?php echo is_null($ship->get('id_generator')) ? 'null' : $ship->get('id_generator') ?>,
		"maxEnergy": <?php echo is_null($ship->get('max_energy')) ? 'null' : $ship->get('max_energy') ?>,
		"energy": <?php echo is_null($ship->get('energy')) ? 'null' : $ship->get('energy') ?>,
		"slots": <?php echo is_null($ship->get('slots')) ? 'null' : $ship->get('slots') ?>,
		"crew": <?php echo is_null($ship->get('crew')) ? 'null' : $ship->get('crew') ?>,
		"credits": <?php echo is_null($ship->get('credits')) ? 'null' : $ship->get('credits') ?>
	}<?php if ($i<count($values['ships'])-1): ?>,<?php endif ?>
<?php endforeach ?>