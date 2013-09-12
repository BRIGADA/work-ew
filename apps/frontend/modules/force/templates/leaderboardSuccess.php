<div class="page-header">
	<h1><?php echo $tournament->dates?></h1>
</div>

<p>
	<button id="current" class="btn btn-primary">Обновить</button>
</p>
<table class="table table-striped table-bordered" id="leaderboard">
	<thead>
		<tr>
			<th style="width: 20px">#</th>
			<th style="width: 30px"></th>
			<th>Force</th>
			<th>Delta</th>
			<th>User</th>
			<th>Prize</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tournament->bout_prizing as $rank => $prize) : ?>
		<?php
			$prizes = array();
			if(isset($prize['platinum'])) $prizes[] = sprintf('%u plat', $prize['platinum']);
			if(isset($prize['trophy'])) $prizes[] = $prize['trophy'];
			if(isset($prize['items'])) foreach ($prize['items'] as $item) $prizes[] = sprintf('%ux %s', $item['quantity'], $item['type']);
		?>
		<tr id="rank-<?php echo $rank?>" data-rank="<?php echo $rank?>">
			<td><?php echo $rank ?></td>
			<td class="force-change"></td>
			<td class="force-power"><?php if(isset($data[$rank])) echo $data[$rank]->power?></td>
			<td class="force-delta"></td>
			<td class="force-user"><?php if(isset($data[$rank])) echo $data[$rank]->user_name?></td>		
			<td><?php echo implode(', ', $prizes)?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<script type="text/javascript">
	var current = <?php echo json_encode($data->toArray()->getRawValue())?>;
	$('#current').click(function(){
		$.ajax({
			url: '<?php echo url_for('force/current')?>',
			data: {
				id: <?php echo $tournament->id ?>
			},
			success: function(result){
				$('#leaderboard > tbody > tr').each(function(){
					var rank = $(this).data('rank');
					if(rank in result)
					{
						$(this).children('.force-change').text('');
						$(this).removeClass();
						for(var key in current)
						{
							if(current[key].user_id == result[rank].user_id) {								
								var delta = result[rank].power-current[key].power;
								$(this).children('.force-delta').text(delta ? delta : '');
								if(rank != key) {
									if(rank < key) {
										$(this).children('.force-change').html('<span style="color: green;">'+(key-rank)+'&uarr;</span>');
										$(this).addClass('success');
									}
									else {
										$(this).children('.force-change').html('<span style="color: red;">'+(rank-key)+'&darr;</span>');
										$(this).addClass('error');
									}									
								}
								break;
							}
						}

						
						$(this).children('.force-power').text(result[rank].power);
						$(this).children('.force-user').text(result[rank].user_name);
					}
				});
				
				current = result;
			},
			error: function(){
				console.log('FAILED CURRENT');
			}
		
		});
	}); 
</script>

<?php //var_dump($data->toArray()->getRawValue())?>