<?php use_helper('I18N')?>
<h1><?php echo __(strtolower($type).'.name', array(), 'ew-items')?></h1>

<table class="table table-bordered table-hover table-condensed table-striped" id="equipment-list">
	<thead>
		<tr>
			<th rowspan="2" style="width: 30px">L</th>
			<th colspan="8">Бонусы</th>
			<th colspan="5">Минусы</th>
			<th rowspan="2" style="width: 50px">Chance</th>
			<th rowspan="2" style="width: 50px">Time</th>
			<th rowspan="2">Tags</th>
		</tr>
		<tr>
			<th style="width: 50px"><abbr title="HP">H</abbr></th>
			<th style="width: 50px"><abbr title="Range">R</abbr></th>
			<th style="width: 50px"><abbr title="Rate">R</abbr></th>
			<th style="width: 50px"><abbr title="Damage">D</abbr></th>
			<th style="width: 50px"><abbr title="Targets">T</abbr></th>
			<th style="width: 50px"><abbr title="Splash">S</abbr></th>
			<th style="width: 50px"><abbr title="Concussion">C</abbr></th>
			<th style="width: 50px"><abbr title="Defense">D</abbr></th>
			<th style="width: 50px"><abbr title="Gas">G</abbr></th>
			<th style="width: 50px"><abbr title="Energy">E</abbr></th>
			<th style="width: 50px"><abbr title="Uranium">U</abbr></th>
			<th style="width: 50px"><abbr title="Cristal">C</abbr></th>
			<th style="width: 50px">SP</th>
		</tr>
		
	</thead>
	<tbody>
		<?php foreach ($levels as $row) : ?>
		<tr>
			<th><?php echo $row->level ?></th>
			<td><?php echo $row->stat_hp ?></td>
			<td><?php echo $row->stat_range ?></td>
			<td><?php echo $row->stat_rate ?></td>
			<td><?php echo $row->stat_damage ?></td>
			<td><?php echo $row->stat_targets ?></td>
			<td><?php echo $row->stat_splash ?></td>
			<td><?php echo $row->stat_concussion ? '&plus;' : '&minus;'?></td>
			<td><?php echo $row->stat_defense ?></td>
			<td><?php echo $row->require_g ?></td>
			<td><?php echo $row->require_e ?></td>
			<td><?php echo $row->require_u ?></td>
			<td><?php echo $row->require_c ?></td>
			<td><?php echo $row->require_s ?></td>
			<td><?php printf('%d%%', $row->upgrade_chance*10) ?></td>
			<td><?php echo $row->time ?></td>
			<td><?php echo $row->tags ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
