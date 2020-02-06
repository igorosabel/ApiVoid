<?php foreach ($values['resources'] as $i=>$resource): ?>
  {
    "resource": {
      "id": <?php echo $resource['id'] ?>,
      "name": "<?php echo urlencode($resource['name']) ?>",
      "credits": <?php echo $resource['credits'] ?>
    },
    "value": <?php echo $resource['value'] ?>
  }<?php if ($i<count($values['resources'])-1): ?>,<?php endif ?>
<?php endforeach ?>