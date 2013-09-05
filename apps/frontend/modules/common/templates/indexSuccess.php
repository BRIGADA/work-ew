<form action="<?php echo url_for('common/set') ?>" method="post" class="form-inline">
	<fieldset>
		<legend>URL</legend>
		<input type="text" name="url" placeholder="Клиентский URL" class="input-xxlarge">
		<button class="btn btn-primary"><i class="icon-ok icon-white"></i> Задать</button>
	</fieldset>
</form>

<?php if($sf_user->hasFlash('success')) : ?>
<div class="alert alert-success"><?php echo $sf_user->getFlash('success') ?></div>
<?php endif ?>

<?php if($sf_user->hasFlash('error')) : ?>
<div class="alert alert-error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif ?>

<form action="<?php echo url_for('common/setClient')?>" class="form-horizontal">
	<fieldset>
		<legend>Client</legend>
		<?php foreach(array('host', 'meltdown', 'reactor', 'user_id', '_session_id', 'testCount') as $param) : ?>		
		<div class="control-group">
			<label class="control-label"><?php echo $param?></label>
			<div class="controls">
				<input type="text" value="<?php echo $sf_user->getAttribute($param, '', 'client') ?>" name="client[<?php echo $param ?>]" placeholder="null">
			</div>
		</div>
		<?php endforeach ?>
		<div class="control-group">
			<div class="controls">
				<button type="submit" class="btn btn-primary">Задать</button>
			</div>
		</div>
	</fieldset>
</form>

<ul>
	<li><a href="<?php echo url_for('common/manifest')?>">Обновление справочников</a></li>
	<li><a href="<?php echo url_for('common/player')?>">Сведения об игроке</a>
	</li>
</ul>

<table class="table">
	<tr>
		<th>meltdown</th>
		<th>reactor</th>
		<th>_session_id</th>
	</tr>
	<?php foreach($history as $row) : ?>
	<tr>
		<td><?php echo $row->meltdown?></td>
		<td><?php echo $row->reactor?></td>
		<td><?php echo $row->_session_id?></td>
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