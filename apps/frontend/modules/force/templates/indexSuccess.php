<div class="page-header">
	<h1>Турниры</h1>
	<a href="<?php echo url_for('force/update')?>" class="btn btn-primary btn-large">Обновить</a>
</div>
<div class="row">
	<div class="span12">
		<table class="table table-striped">
			<tr>
				<th>Даты</th>
				<th>Тип</th>
				<th>Завершается</th>
				<th>Калькуляция</th>
				<th>Улучшения</th>
			</tr>
			<?php foreach ($tournaments as $tournament) : ?>
			<tr>
				<td><a href="<?php echo url_for("force/leaderboard?id={$tournament->id}")?>"><?php echo $tournament->dates ?></a></td>
				<td><?php echo $tournament->type ?></td>
				<td style="color: <?php echo ($tournament->end_at > time()) ? 'green' : 'red' ?>"><?php echo date(DATE_W3C, $tournament->end_at)?></td>
				<td>
					<ul>
						<?php foreach ($tournament->active_calculations as $row) : ?>
						<li><?php echo $row ?></li>
						<?php endforeach ?>
					</ul>
				</td>
				<td>
					<ul>
						<?php foreach ($tournament->value_adjustments as $key => $value) : ?>
						<li><?php echo $key?> (<?php echo implode('-', $value->getRawValue())?>)</li>
						<?php endforeach ?>
					</ul>
				</td>
			</tr>
			<?php endforeach ?>
		</table>
	</div>
</div>