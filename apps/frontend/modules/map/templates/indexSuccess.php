<h1>Карты</h1>

<table class="table table-striped table-bordered table-hover" id="maps">
	<thead>
		<tr>
			<th>Type</th>
			<th>Active</th>
			<th>Size</th>
			<th>Max Nodes</th>
			<th>Max Level</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($result as $n => $map):?>
		<tr data-id="<?php echo $map->id ?>">
			<td><?php echo link_to($map->type, "map/{$map->id}")?></td>
			<td><?php if($map->active) : ?>+<?php else : ?>-<?php endif ?></td>
			<td><?php echo $map->width ?> x <?php echo $map->height?></td>
			<td><?php echo $map->max_territory_limit ?></td>
			<td><?php echo $map->maximum_node_level ?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>
