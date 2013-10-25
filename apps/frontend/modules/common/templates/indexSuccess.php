<?php if($sf_user->hasFlash('success')) : ?>
<div class="alert alert-success"><?php echo $sf_user->getFlash('success') ?></div>
<?php endif ?>

<?php if($sf_user->hasFlash('error')) : ?>
<div class="alert alert-error"><?php echo $sf_user->getFlash('error') ?></div>
<?php endif ?>

<legend>Client</legend>

<p>
    <a href="#clientform" data-toggle="modal" class="btn">Изменить</a>
    <a href="#parseurlform" data-toggle="modal" class="btn">URL</a>
</p>

<form action="<?php echo url_for('common/setClient')?>" method="post" class="modal hide fade" id="clientform">
  <div class="modal-header">
    <h3>Изменение параметров клиента</h3>
  </div>
	<div class="modal-body"><?php echo $clientForm ?></div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Отправить</button>
	</div>
</form>

<form action="<?php echo url_for('common/setURL')?>" method="post" class="modal hide fade" id="parseurlform">
  <div class="modal-header">
    <h3>Распарсить URL</h3>
  </div>
	<div class="modal-body">
		<input type="text" name="url" placeholder="Клиентский URL" class="input-block-level" required="required">
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-primary">Отправить</button>
	</div>
</form>

<table class="table table-striped table-bordered table-condensed">
	<tr>
		<th>UserAgent</th>
		<td><?php echo htmlentities($_SERVER['HTTP_USER_AGENT']) ?></td>
	</tr>
	<tr>
		<th>testCount</th>
		<td>
		  <?php echo $sf_user->getAttribute('testCount', 1, 'player/data')?>
		  <a href="#" class="btn btn-mini">Сброс</a>
		</td>
	</tr>
	<tr>
		<th>host</th>
		<td><?php echo $sf_user->getAttribute('host', '', 'player/data')?></td>
	</tr>
	<tr>
		<th>reactor</th>
		<td><?php echo $sf_user->getAttribute('reactor', '', 'player/data')?></td>
	</tr>
	<tr>
		<th>_session_id</th>
		<td><?php echo $sf_user->getAttribute('_session_id', '', 'player/data')?></td>
	</tr>
	<tr>
		<th>user_id</th>
		<td><?php echo $sf_user->getAttribute('user_id', '', 'player/data')?></td>
	</tr>
</table>

<legend>Proxy</legend>
<a href="<?php echo url_for('common/login')?>">LOGIN</a>
<?php if($proxy_status === null) : ?>
<p class="text-error">NOT READY</p>
<?php else : ?>
<p class="text-success">READY</p>
<?php endif ?>

<legend>Meltdown</legend>

<table class="table table-striped table-condensed table-hover table-bordered">
	<?php foreach($meltdowns as $row) : ?>
	<?php if($row->created_at > date("Y-m-d H:i:s", time() - 60*60)) :?>
	<tr class="success">
	<?php else : ?>
	<tr>
	<?php endif ?>
		<td><span style="font-family: courier; font-size: 1.5em;"><?php echo $row->value ?></span></td>
		<td><?php echo $row->created_at ?></td>
	</tr>
	<?php endforeach ?>
</table>


<script type="text/javascript">
$(function(){
	setTimeout(function(){
		$('.alert').fadeOut(function(){
			$(this).remove();
		});		
	}, 3000);
});
</script>