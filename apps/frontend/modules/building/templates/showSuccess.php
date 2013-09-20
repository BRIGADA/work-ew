<div class="page-header">
<h1><?php echo $building->getType() ?></h1>
<div class="lead">Размер: <?php echo $building->size[0] ?>x<?php echo $building->size[1] ?></div>
</div>

<table class="table table-striped">
	<tr>
		<th>L</th>
		<th>Time</th>
		<th>Requirements</th>
		<th>Stats</th>
	</tr>
	<?php foreach($building->levels as $row) : ?>
	<tr>
		<th><?php echo $row->level ?></th>
		<td><?php echo $row->time ?></td>
		<td>
		<?php if(count($row->requirements)) : ?>
			<ul>
				<?php foreach($row->requirements->getRawValue() as $k => $v) : ?>
				<?php if(is_object($v)) : ?>
				<li>
					<?php echo $k ?>
					<ul>
					<?php foreach($v as $vn => $vv) : ?>
					<li><?php echo $vn?>: <?php echo $vv?></li>
					<?php endforeach ?>
					</ul>
				
				</li>
				<?php else : ?>
				<li><?php echo $k ?>: <?php echo $v?></li>
				<?php endif ?>
				<?php endforeach ?>
			</ul>
			<?php endif ?>
		</td>
		<td><?php //var_dump((array)$row->stats->getRawValue())?></td>
	</tr>
	<?php endforeach ?>
</table>
<hr />
<?php var_dump($requirements)?>

<a href="<?php echo url_for('building/index') ?>" class="btn">Назад</a>
