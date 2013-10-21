<?php use_helper('I18N')?>
<?php use_javascript('jquery.sortElements.js')?>
<?php use_javascript('bootbox.min.js')?>
<?php use_javascript('http://code.highcharts.com/highcharts.js')?>

<div class="page-header">
	<h1><?php echo __('Equipment')?></h1>
</div>

<audio src="<?php echo url_for('/buzz.ogg') ?>" preload="auto" id="sound-error"></audio>

<div class="row">
	<div class="span12">
		<p>
			<button id="action-upgrade-all" class="btn"><i class="icon-arrow-up"></i> <?php echo __('Upgrade all')?></button>
			<button id="action-delete-all" class="btn"><i class="icon-trash"></i> <?php echo __('Delete all')?></button>
			<button id="action-repair-all" class="btn"><i class="icon-refresh"></i> <?php echo __('Repair all')?></button>
			<button id="action-craft" class="btn">Craft</button>
			<button id="action-cheat" class="btn">:)</button>
			
		</p>
	</div>
	<div class="span12">
		<table class="table table-bordered table-hover table-condensed" id="equipment-list">
			<thead>
				<tr>
					<td>
					   <!-- 
						<select id="filter-type" multiple="multiple" style="width: 100%">
							<?php //foreach ($types as $type) : ?>
							<option value="<?php //echo $type ?>"><?php //echo __(strtolower($type).'.name', array(), 'ew-items') ?></option>
							<?php //endforeach ?>
						</select>
						-->
					</td>
					<td style="width: 40px">
						<select id="filter-level" multiple="multiple" style="width: 40px;">
							<?php foreach($levels as $level) : ?>
								<option><?php echo $level ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td style="width: 80px;">
						<select id="filter-durability" style="width: 80px;">
							<option value="-1">&mdash;</option>
							<option value="0">Сломан</option>
							<option value="1">Целый</option>
						</select>
					</td>
					<td style="width: 60px">
						<select id="filter-equipped" style="width: 60px">
							<option value="-1">&mdash;</option>
							<option value="1">Да</option>
							<option value="0">Нет</option>
						</select>
					</td>
					<td style="width: 40px;">
						<select id="filter-tier" multiple="multiple" style="width: 40px;">
							<?php foreach($tiers as $tier) : ?>
							<option><?php echo $tier ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td colspan="<?php echo count($stats) ?>">
						<select id="filter-stats" multiple="multiple" style="width: 100%">
							<?php foreach ($stats as $stat) : ?>
							<option value="<?php echo $stat ?>"><?php echo $stat ?></option>
							<?php endforeach ?>
						</select>
					</td>
					<td rowspan="2" style="width: 100px;"></td>
				</tr>
				<tr>
					<th>type</th>
					<th>level</th>
					<th>healh</th>
					<th>used</th>
					<th>tier</th>
					<?php foreach($stats as $i => $stat ) : ?>
					<th style="width: 16px"><abbr title="<?php echo $stat ?>"><?php echo $i ?></abbr></th>
					<?php endforeach ?>
				</tr>
			</thead>
			<tbody>
				<?php foreach ($results as $row ): ?>
				<tr data-id="<?php echo $row->id ?>" data-type="<?php echo $row->type ?>">
					<td><?php echo __(strtolower($row->type).'.name', array(), 'ew-items') ?></td>
					<td class="cell-level"><?php echo $row->level ?></td>
					<td class="cell-durability"><?php echo $row->durability ?></td>
					<td class="cell-equipped"><?php if($row->equipped) : ?>+<?php else : ?>-<?php endif ?></td>
					<td class="cell-tier"><?php echo $manifest[$row->type]['levels'][$row->level]['tier'] ?></td>
					<?php foreach ($stats as $stat) : ?>
					<td class="cell-stat-<?php echo $stat ?>" style="text-align: center;"><?php if(isset($manifest[$row->type]['levels'][$row->level]['stats'][$stat]) && ($manifest[$row->type]['levels'][$row->level]['stats'][$stat] != 'false') && $manifest[$row->type]['levels'][$row->level]['stats'][$stat]) : ?>+<?php endif ?></td>
					<?php endforeach ?>
					<td>
						<a href="#" class="btn btn-mini action-trash"><i class="icon-trash"></i></a>
						<a href="#" class="btn btn-mini action-upgrade"><i class="icon-arrow-up"></i></a>
						<a href="#" class="btn btn-mini action-repair"><i class="icon-refresh"></i></a>
					</td>
				</tr>
				<?php endforeach ?>
			</tbody>
			<tfoot>
				<tr>
					<th colspan="<?php echo 6+count($stats) ?>"></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>

<ul class="thumbnails" id="charts">
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
	<li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
</ul>

<script type="text/javascript">
var manifest = <?php echo json_encode($manifest->getRawValue(), JSON_NUMERIC_CHECK) ?>;
var stats = <?php echo json_encode($stats->getRawValue())?>;

var fails = 0;

$(function(){
	function updateCounter() {
		$('#equipment-list > tfoot th').text($('#equipment-list > tbody > tr:visible').size() + ' / ' + $('#equipment-list > tbody > tr').size());
	}

	updateCounter();

	$('#action-cheat').click(function(){
		var set = $('#equipment-list > tbody > tr:visible');
		fails=0;

		function cheat(index) {
			nodeUP(set.eq(index), function(){
				index++;
				if(index >= set.size() ) index = 0;
				cheat(index);
			});
		}
		up(0);
	});
	
	$('#filter-type, #filter-level, #filter-durability, #filter-equipped, #filter-tier, #filter-stats').change(function(){
		$('#equipment-list > tbody').trigger('update-view');
	});

	$('#equipment-list > tbody').on('update-view', function(){		
/*
		$(this).children('tr').sortElements(function(a, b){
			var l1 = parseInt($(a).children('.cell-level').text());
			var l2 = parseInt($(b).children('.cell-level').text());

			if(l1 == l2) return parseInt($(a).data('id')) > parseInt($(b).data('id')) ? 1 : -1;
			return  l1 > l2 ? 1 : -1;
		});
*/		

		$(this).children('tr').each(function(){
			var v = true;
			if($('#filter-type').val() && ($('#filter-type').val().indexOf($(this).data('type')) == -1)) { 
				v = false;
			}
			if($('#filter-level').val() && ($('#filter-level').val().indexOf($(this).children('.cell-level').text()) == -1)) {
				v = false;
			}

			if($('#filter-tier').val() && ($('#filter-tier').val().indexOf(String(manifest[$(this).data('type')].levels[parseInt($(this).children('.cell-level').text())].tier)) == -1)) {
				v = false;
			}
			var b = $('#filter-stats').val();
			if(b) {
				for(var i in b) {
					var e = (manifest[$(this).data('type')].levels[parseInt($(this).children('.cell-level').text())].stats[b[i]]);
					if(e) {
						v = false;
						break;
					}
				}
			}

			switch($('#filter-durability').val()) {
			case '0':
				if(parseInt($(this).children('.cell-durability').text()) != 0) v = false;
				break;
			case '1':
				if(parseInt($(this).children('.cell-durability').text()) == 0) v = false;
				break;				
			}

			switch($('#filter-equipped').val()) {
			case '0':
				if($(this).children('.cell-equipped').text() == '+') v = false;
				break;
			case '1':
				if($(this).children('.cell-equipped').text() == '-') v = false;
				break;
			}

			$(this).toggle(v);
		});

		updateCounter();

	}).trigger('update-view');

	$('#action-delete-all').click(function(){
		var set = $('#equipment-list > tbody > tr:visible');
		$.ajax({
			url: '<?php echo url_for('equipment/multidestroy')?>',
			data: {
				ids: set.map(function(){
					return $(this).data('id');
				}).get()
			},
			success: function(answer){
				set.remove();
				updateCounter();
				bootbox.alert('Успешно удалено');
			},
			error: function(){
				bootbox.alert('Ошибка удаления');				
			}
		});
		return false;
	});

	$('#action-upgrade-all').click(function(){
		function upgrade() {
			var set = $('#equipment-list > tbody > tr:visible');
			for(var i = 0; i < set.length; i++) {
				if(parseInt(set.eq(i).children('.cell-durability').text()) != 0) {
					nodeUpgrade(set.eq(i), function(){
						upgrade();
					});
					return;
				}
			}
			
			$('#sound-error').get(0).play();
			bootbox.alert('Обновление завершено.');
		}
		upgrade(); 
		return false;
	});

	function nodeUpgrade(node, callback) {
		callback = callback || function(){};
				
		node.removeClass().addClass('info');		
		$.ajax({
			url: '<?php echo url_for('equipment/upgrade')?>',
			data: {
				id: node.data('id')
			},
			type: 'post',
			success: function(response) {
				var level = parseInt(node.children('.cell-level').text());
				var chart = $('#charts li').eq(level > 6 ? 5 : (level - 1));
				
				node.removeClass();
				
				if(response.successful) {
					node.addClass('success');
					chart.data('success', chart.data('success') + 1);
				}
				else {
					node.addClass('error');
					chart.data('error', chart.data('error') + 1);
				}

				chart.children('div').highcharts({
					chart: {
						animation: false,
						plotBackgroundColor: null,
						plotBorderWidth: null,
						plotShadow: false
					},
					tooltip: {
						pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y})'
					},
					credits: {
						enabled: false
					},
					title: {
						text: null
					},
					plotOptions: {
						pie: {
							animation: false,
							dataLabels: {
								enabled: false
							}
						}
					},
					series: [{
						type: 'pie',
						data: [chart.data('success'), chart.data('error')]
					}]
				});
				
				node.children('.cell-durability').text(response.equipment.durability);
				node.children('.cell-level').text(response.equipment.level);
			},
			error: function(){
				node.children('.cell-durability').text(0);
				node.removeClass().addClass('warning');				
			},
			complete: function(){
				callback();
			}
		});
	}

	function nodeRepair(node, callback) {
		callback = callback || function(){};

		if(node.data('repairing')) {
			callback();
		}

		node.removeClass().addClass('info');
		$.post('<?php echo url_for('equipment/repair')?>', { id: node.data('id') }, function(response){
			console.log(response);
			node.data('repairing', true);
			node.addClass('muted');
			callback();
		});
	}

	$('.action-repair').click(function(){
		nodeRepair($(this).closest('tr'));
		return false;
	});

	$('.action-upgrade').click(function(){
		nodeUpgrade($(this).closest('tr'));
		return false;
	});

	$('#action-craft').click(function(){
		var set1 = $('#equipment-list > tbody > tr:visible .cell-tier:contains(1)').closest('tr');
		var set2 = $('#equipment-list > tbody > tr:visible .cell-tier:contains(2)').closest('tr');

		function craft(items, count, type, callback) {
			console.log('enter craft()');
			if(items.length < count) {
				console.log('items.length < count');
				callback();
				return;
			}

			var current = items.splice(0, count);

			$(current).removeClass().addClass('info');

			$.ajax({
				url: '<?php echo url_for('equipment/craft') ?>',
				data: {
					name: type,
					ids: $(current).map(function(){ return $(this).data('id'); }).get()
				},
				success: function(response) {
					console.log(response);
					$(current).remove();
					updateCounter();
					craft(items, count, type, callback);
				},
				error: function(){
					console.log('error');
				}
			});
		}

		craft(set1, 10, 'rarebox1a', function(){
			craft(set2, 5, 'rarebox1b', function(){});
		});
	});

	$('#action-repair-all').click(function(){
		var set = $('#equipment-list > tbody > tr:visible');
	
		function repair(index){
			if(index >= set.length) {
				bootbox.alert('Починка завершена!');
				return;
			}
			var r = set.eq(index);
			if(r.data('repairing') || parseInt(r.children('.cell-durability').text()) == 1000) {
				repair(index + 1);
				return;
			}
			r.data('repairing', true);		
			r.removeClass().addClass('muted');	
			$.post('<?php echo url_for('equipment/repair') ?>', { id: r.data('id')}, function(){				
				repair(index + 1);
			});
		}

		repair(0);
		return false;
	});

});

/*
$('#action-craft').click(function(){
	var items1a = [];
	var items1b = [];
	$('#equipment-list > tbody > tr:visible').each(function(){
		if($(this).data();
	});
});
	*/

function LOG(message, decoration) {
	console.log(message);
	var record = $('<li>').html(message);
	if(decoration) record.addClass(decoration);	
	$('#equipment-log').prepend(record);
}
</script>

