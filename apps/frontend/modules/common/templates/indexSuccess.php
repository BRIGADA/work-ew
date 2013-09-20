<?php if($sf_user->hasFlash('success')) : ?>
<div class="alert alert-success"><?php echo $sf_user->getFlash('success') ?></div>
<?php endif ?>

<?php if($sf_user->hasFlash('error')) : ?>
<div class="alert alert-error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif ?>

<form action="<?php echo url_for('common/set') ?>" method="post" class="form-inline">
	<fieldset>
		<legend>URL</legend>
		<input type="text" name="url" placeholder="Клиентский URL" class="input-xxlarge">
		<button class="btn btn-primary"><i class="icon-ok icon-white"></i> Задать</button>
	</fieldset>
</form>

<form action="<?php echo url_for('common/setClient')?>" class="form-horizontal">
	<fieldset>
		<legend>Client</legend>
		<?php foreach(array('host', 'reactor', 'user_id', '_session_id', 'testCount') as $param) : ?>		
		<div class="control-group">
			<label class="control-label"><?php echo $param?></label>
			<div class="controls">
				<input type="text" value="<?php echo $sf_user->getAttribute($param, '', 'playerVO') ?>" name="client[<?php echo $param ?>]" placeholder="null">
			</div>
		</div>
		<?php endforeach ?>
		<div class="control-group">
			<label class="control-label">meltdown</label>
			<div class="controls">
				<input type="text" value="<?php MeltdownTable::getLast() ?>" name="client[meltdown]" placeholder="null">
			</div>
		</div>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary"><i class="icon-ok icon-white"></i> Задать</button>
			</div>
		</div>
	</fieldset>
</form>

<table class="table table-striped table-condensed table-hover">
	<tr>
		<th>meltdown</th>
		<th>timestamp</th>
	</tr>
	<?php foreach($meltdowns as $row) : ?>
	<tr>
		<td><span style="font-family: courier; font-size: 1.5em;"><?php echo $row->value ?></span></td>
		<td><?php echo $row->created_at ?></td>
	</tr>
	<?php endforeach ?>
</table>


<script type="text/javascript">
$(function(){
	setTimeout(function(){
		$('.alert').fadeOut();		
	}, 3000);
});
</script>