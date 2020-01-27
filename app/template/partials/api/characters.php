<?php foreach ($values['characters'] as $i => $char): ?>
  {
    "id": <?php echo $char['id'] ?>,
  	"type": <?php echo $char['type'] ?>,
  	"name": "<?php echo urlencode($char['name']) ?>"
  }<?php if ($i<count($values['characters'])-1): ?>,<?php endif ?>
<?php endforeach ?>