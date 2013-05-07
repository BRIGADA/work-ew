<form action="<?php echo url_for('common/set') ?>" method="post" class="form-inline">
	<input type="text" name="url" placeholder="Клиентский URL" class="input-xxlarge">
	<button class="btn btn-primary"><i class="icon-ok icon-white"></i> Задать</button>
</form>

<?php if($sf_user->hasFlash('success')) : ?>
<div class="alert alert-success"><?php echo $sf_user->getFlash('success') ?></div>
<?php endif ?>

<?php if($sf_user->hasFlash('error')) : ?>
<div class="alert alert-error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif ?>

<table class="table table-striped table-bordered table-hover">
	<thead>
		<tr>
			<th style="width: 100px">Параметр</th>
			<th>Значение</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach(array('host', 'meltdown', 'reactor', 'user_id', '_session_id', 'testCount') as $param) : ?>
		<tr>
			<th><?php echo $param ?></th>
			<td>
				<?php if($sf_user->hasAttribute($param, 'client')) :?>
				<?php echo $sf_user->getAttribute($param, null, 'client')?>
				<?php else : ?>
				<span class="muted"><em>null</em></span>
				<?php endif ?>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<script type="text/javascript">
$(function(){
	setTimeout(function(){
		$('.alert').fadeOut();		
	}, 3000);
});
</script>