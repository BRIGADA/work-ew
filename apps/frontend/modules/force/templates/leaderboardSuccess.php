<div class="page-header">
	<h1><?php echo $tournament->dates?></h1>
</div>

<p>
	<button id="current" class="btn btn-primary">Обновить</button>
	<button id="testbtn" class="btn btn-primary">TEST</button>
</p>
<table class="table table-striped table-bordered" id="leaderboard">
	<thead>
		<tr>
			<th style="width: 20px">#</th>
			<th style="width: 30px"></th>
			<th>Force</th>
			<th>Delta</th>
			<th>UP</th>
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
			<td class="force-up"><?php if(isset($prev_force)) : ?><?php echo ($prev_force - $data[$rank]->power); ?><?php endif ?><?php $prev_force = $data[$rank]->power; ?></td>
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
				var prev_force = null;
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
						if(prev_force != null) {
							$(this).children('.force-up').text(prev_force - result[rank].power);
						}
						prev_force = result[rank].power;
					}
				});
				
				current = result;
			},
			error: function(){
				console.log('FAILED CURRENT');
			}
		
		});
	}); 

$('#testbtn').click(function(){
	$.ajax({
		url: 'http://google.ru',
		type: 'POST',
		beforeSend: function(request){
			request.setRequestHeader('x-s3-cachebreak', 'aaaaaaaa');
		},		
		data: '_session_id=adasdasdasdasd&meltdown=12312312312312312&user_id=333333',
		processData: false,
		success: function(result){
			console.log(result);
		},
		error: function(qXHR, textStatus, errorThrown){
			console.log('ajax.error');
			console.log(qXHR);
			console.log(textStatus);
			console.log(errorThrown);
		}
	});
	return false;
});
</script>

<?php //var_dump($data->toArray()->getRawValue())?>