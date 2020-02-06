<?php foreach ($values['modules'] as $i=>$module): ?>
  {
    "id": <?php echo $module->get('id') ?>,
    "name": "<?php echo urlencode($module->get('name')) ?>",
    "idType": <?php echo is_null($module->get('id_type')) ? 'null' : $module->get('id_type') ?>,
    "engine": <?php echo is_null($module->get('engine')) ? 'null' : $module->get('engine') ?>,
    "shield": <?php echo is_null($module->get('shield')) ? 'null' : $module->get('shield') ?>,
    "cargo": <?php echo is_null($module->get('cargo')) ? 'null' : $module->get('cargo') ?>,
    "damage": <?php echo is_null($module->get('damage')) ? 'null' : $module->get('damage') ?>,
    "crew": <?php echo is_null($module->get('crew')) ? 'null' : $module->get('crew') ?>,
    "energy": <?php echo is_null($module->get('energy')) ? 'null' : $module->get('energy') ?>,
    "slots": <?php echo is_null($module->get('slots')) ? 'null' : $module->get('slots') ?>,
    "credits": <?php echo is_null($module->get('credits')) ? 'null' : $module->get('credits') ?>
  }<?php if ($i<count($values['modules'])-1): ?>,<?php endif ?>
<?php endforeach ?>