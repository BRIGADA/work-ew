<div class="page_header">
	<h1>Proxy</h1>
</div>

<div>
	<form action="<?php echo url_for('proxy/index')?>">
		<select name="filter" onchange="submit();">
			<option value="">&mdash;</option>
			<?php foreach($types as $type) : ?>
			<?php if($filter == $type) : ?>
			<option selected="selected"><?php echo $type ?></option>
			<?php else : ?>
			<option><?php echo $type ?></option>
			<?php endif ?>
			<?php endforeach ?>
		</select>
	</form>
	<table class="table">
		<?php if(!$filter) : ?>
		<tr>
			<th>Тип</th>
			<th>Данные</th>
		</tr>
		<?php endif ?>
		<?php foreach($pager->getResults() as $row) : ?>
		<tr>
			<?php if(!$filter) : ?>
			<td><?php echo $row->type ?></td>
			<?php endif ?>
			<td><?php var_dump($row->params->getRawValue())?></td>
		</tr>
		<?php endforeach ?>
	</table>
</div>
