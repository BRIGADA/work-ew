<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Кампания &laquo;<?php echo __(sprintf('campaign.%s.name', $campaign->name), array(), 'ew-ew')?>&raquo;</h1>
	<a href="<?php echo url_for('@manifest-campaigns')?>" class="btn btn-large">Список кампаний</a>
</div>

<table class="table table-striped">
	<tr>
		<th>Имя</th>
		<th>Уровень открытия</th>
		<th>Очки</th>
		<th>Атакующий<br>(уровень + усиление)</th>
		<th>Уровень юнитов</th>
		<th>Волны</th>		
	</tr>
<?php foreach($campaign->stages as $stage ):?>
	<tr>
		<th><?php echo $stage->name ?></th>
		<td><?php echo $stage->player_unlock_level ?></td>
		<td><?php echo $stage->baseline_xp ?></td>
		<td><?php echo $stage->attacker_level ?> + <?php echo $stage->attacker_boost ?></td>
		<td><?php echo $stage->unit_level ?></td>
		<td>
			<ul>
				<?php foreach($stage->units as $unit):?>
				<li><?php echo $unit->quantity ?>x <?php echo __(sprintf('%s.name', strtolower($unit->type)), array(), 'ew-units')?> (<?php echo $unit->x?>, <?php echo $unit->y?>) &mdash; <?php echo $unit->time ?></li>
				<?php endforeach ?>
			</ul>
		</td>
	</tr>
<?php endforeach ?>  
</table>
