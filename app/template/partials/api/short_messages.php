<?php foreach ($values['messages'] as $i => $message): ?>
  {
    "id": <?php echo $message->get('id') ?>,
  	"type": <?php echo $message->get('type') ?>,
  	"name": "<?php echo urlencode($message->getFrom()->get('name')) ?>",
  	"date": "<?php echo $message->get('created_at', 'd/m/Y H:i:s') ?>",
  	"message": "<?php echo urlencode($message->get('message')) ?>"
  }<?php if ($i<count($values['messages'])-1): ?>,<?php endif ?>
<?php endforeach ?>