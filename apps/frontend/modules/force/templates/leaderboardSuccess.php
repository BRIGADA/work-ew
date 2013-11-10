<?php use_helper('I18N')?>
<div class="page-header">
	<h1><?php echo $tournament->dates?></h1>
</div>

<div class="progress" id="update-countdown" style="height: 40px;" title="click for update">
	<div class="progress-bar" style="width: 0%;"></div>
</div>


<table class="table table-striped table-bordered" id="leaderboard">
	<thead>
		<tr>
			<th style="width: 20px">#</th>
			<th style="width: 30px"></th>
			<th style="width: 100px">Force</th>
			<th style="width: 100px">UP</th>
			<th style="width: 100px">Delta</th>
			<th>User</th>
			<th>Prize</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($tournament->bout_prizing as $rank => $prize) : ?>
		<?php
			$prizes = array();
			if(isset($prize['platinum'])) $prizes[] = sprintf('<strong class="text-success">%u plat</strong>', $prize['platinum']);
			if(isset($prize['trophy'])) $prizes[] = get_partial('manifest/itemLink', array('type'=>$prize['trophy']));
			if(isset($prize['items'])) foreach ($prize['items'] as $item) $prizes[] = $item['quantity'].'x '.get_partial('manifest/itemLink', array('type' => $item['type']));
		?>
		<tr id="rank-<?php echo $rank?>" data-rank="<?php echo $rank?>">
			<td><?php echo $rank ?></td>
			<td class="force-change"></td>
			<td class="force-power" title=""><?php if(isset($data[$rank])) echo $data[$rank]->power?></td>
			<td class="force-up muted"><?php if(isset($prev_force)) : ?><?php echo ($prev_force - $data[$rank]->power); ?><?php endif ?><?php $prev_force = $data[$rank]->power; ?></td>
			<td class="force-delta"></td>
			<td class="force-user"><?php if(isset($data[$rank])) echo $data[$rank]->user_name?></td>
			<td><?php echo implode(', ', $prizes)?></td>
		</tr>
		<?php endforeach ?>
	</tbody>
</table>

<script type="text/javascript">
var t1 = 0;
var t2 = 600;
var current = <?php echo json_encode($data->toArray()->getRawValue())?>;

$(document).ready(function(){
	var iid = setInterval(function() {
		if(<?php echo $tournament->end_at ?> <= Math.round((new Date()).valueOf()/1000)) {
			clearInterval(iid);
			return;
		}
		t1++;
		if(t1 >= t2) {
			update();
		}
		$('#update-countdown .progress-bar').css('width', (t1 * 100 / t2)+'%');		
	}, 1000);
});

$('#update-countdown').click(function(){
	update();
});

function update(){
	t1 = 0;
	$.ajax({
		url: '<?php echo url_for('force/current')?>',
		data: {	id: <?php echo $tournament->id ?>	},
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
}


</script>