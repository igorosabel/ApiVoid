<?php if (is_null($values['system'])): ?>
  null
<?php else: ?>
<?php $s = $values['system']; ?>
  {
		"id": <?php echo $s->get('id') ?>,
		"name": "<?php echo urlencode($s->get('name')) ?>",
		"type": "<?php echo urlencode($s->get('type')) ?>",
		"typeLink": "<?php echo urlencode($s->getSystemInfoLink()) ?>",
		"typeDesc": "<?php echo urlencode($s->getTypeName()) ?>",
		"idDiscoverer": <?php echo $s->getDiscoverer()->get('id') ?>,
		"discoverer": "<?php echo urlencode($s->getDiscoverer()->get('name')) ?>",
		"radius": <?php echo $s->get('radius') ?>,
		"numNPC": <?php echo $s->get('num_npc') ?>,
		"planets": [
<?php foreach ($s->getPlanets() as $i => $p): ?>
      {
        "id": <?php echo $p->get('id') ?>,
        "name": "<?php echo urlencode($p->get('name')) ?>",
        "type": "<?php echo urlencode($p->get('type')) ?>",
        "typeLink": "<?php echo urlencode($p->getPlanetInfoLink()) ?>",
        "typeDesc": "<?php echo urlencode($p->getTypeName()) ?>",
        "distance": <?php echo $p->get('distance') ?>,
        "radius": <?php echo $p->get('radius') ?>,
        "rotation": <?php echo $p->get('rotation') ?>,
        "explored": <?php echo $p->get('explored') ? 'true' : 'false' ?>,
        "exploreTime": <?php echo $p->get('explore_time') ?>,
        "resources": [
<?php foreach ($p->getResources() as $j => $r): ?>
          {
            "id": <?php echo $r->getResource()['id'] ?>,
            "name": "<?php echo urlencode($r->getResource()['name']) ?>",
            "value": <?php echo $r->getResource()['value'] ?>
          }<?php if ($j<count($p->getResources())-1): ?>,<?php endif ?>
<?php endforeach ?>
        ],
        "moons": [
<?php foreach ($p->getMoons() as $j => $m): ?>
          {
            "id": <?php echo $m->get('id') ?>,
            "name": "<?php echo urlencode($m->get('name')) ?>",
            "distance": <?php echo $m->get('distance') ?>,
            "radius": <?php echo $m->get('radius') ?>,
            "rotation": <?php echo $m->get('rotation') ?>,
            "explored": <?php echo $m->get('explored') ? 'true' : 'false' ?>,
            "exploreTime": <?php echo $m->get('explore_time') ?>,
            "resources": [
<?php foreach ($m->getResources() as $k => $r): ?>
              {
                "id": <?php echo $r->getResource()['id'] ?>,
                "name": "<?php echo urlencode($r->getResource()['name']) ?>",
                "value": <?php echo $r->getResource()['value'] ?>
              }<?php if ($k<count($m->getResources())-1): ?>,<?php endif ?>
<?php endforeach ?>
            ]
          }<?php if ($j<count($p->getMoons())-1): ?>,<?php endif ?>
<?php endforeach ?>
        ]
      }<?php if ($i<count($s->getPlanets())-1): ?>,<?php endif ?>
<?php endforeach ?>
    ]
  }
<?php endif ?>