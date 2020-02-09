<?php if (is_null($values['system'])): ?>
  null
<?php else: ?>
<?php $s = $values['system']; ?>
  {
		"id": <?php echo $s->get('id') ?>,
		"name": "<?php echo urlencode($s->get('name')) ?>",
		"type": "<?php echo urlencode($s->get('type')) ?>",
		"typeLink": "https...",
		"typeDesc": "Estrellas enanas de la secuencia principal",
    "idDiscoverer": <?php echo $s->getDiscoverer()->get('id') ?>,
		"discoverer": "<?php echo urlencode($s->getDiscoverer()->get('name')) ?>",
		"radius": <?php echo $s->get('radius') ?>,
		"numNPC": <?php echo $s->get('num_npc') ?>,
		"planets": [

    ]
  }
<?php endif ?>