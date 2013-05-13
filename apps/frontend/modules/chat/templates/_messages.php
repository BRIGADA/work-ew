<?php foreach ($result as $msg) : ?>
<div class="well" data-id="<?php echo $msg['id'] ?>">
	<div>
		<small><strong><?php echo $msg['sender']?> </strong>, <span class="muted"><?php echo $msg['created_at']?> </span> </small>
	</div>
	<div>
		<em><?php echo $msg['message']?> </em>
	</div>
</div>
<?php endforeach ?>
