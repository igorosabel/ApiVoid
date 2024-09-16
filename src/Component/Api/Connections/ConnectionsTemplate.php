<?php foreach ($values['connections'] as $i => $conn): ?>
	{
		"idSystemEnd": <?php echo (is_null($conn->get('id_system_end'))) ? 'null' : $conn->get('id_system_end') ?>,
		"order": <?php echo $conn->get('order') ?>,
		"navigateTime": <?php echo $conn->get('navigate_time') ?>,
		"name": <?php echo (is_null($conn->get('id_system_end'))) ? 'null' : '"'.urlencode($conn->getSystemEnd()->get('name')).'"' ?>
	}<?php if ($i<count($values['connections'])-1): ?>,<?php endif ?>
<?php endforeach ?>
