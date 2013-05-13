<?php use_helper('I18N')?>
<?php use_javascript('jquery.sortElements.js')?>
<?php use_javascript('bootbox.min.js')?>

<h1><?php echo __('Equipment')?></h1>
<table class="table table-bordered table-hover table-condensed" id="equipment-list">
	<thead>
		<tr>
			<td>
				<select id="filter-type" multiple="multiple" class="span5">
				<?php foreach ($types as $t) : ?>
					<option value="<?php echo $t ?>"><?php echo __(strtolower($t).'.name', array(), 'ew-items') ?></option>
				<?php endforeach ?>
				</select>
			</td>
			<td colspan="8">
				<select id="filter-bonus" multiple="multiple" class="span2">
					<option value="hp">HP</option>
					<option value="range">Range</option>
					<option value="rate">Rate</option>
					<option value="damage">Damage</option>
					<option value="targets">Targets</option>
					<option value="splash">Splash</option>
					<option value="concussion">Concussion</option>
					<option value="defense">Defense</option>
				</select>
			</td>
			<td>
				<select id="filter-level" multiple="multiple" class="span1">
				<?php for($i = 1; $i <= 16; ++$i) : ?>
					<option><?php echo $i ?></option>
				<?php endfor ?>
				</select>
			</td>
			<td>
				<select id="filter-durability" class="span1">
					<option value="-1">&mdash;</option>
					<option value="0">Сломан</option>
					<option value="1">Целый</option>
				</select>
			</td>
			<td>
				<select id="filter-equipped" class="span1">
					<option value="-1">&mdash;</option>
					<option value="1">Да</option>
					<option value="0">Нет</option>
				</select>
			</td>
			<td rowspan="2"></td>
		</tr>
		<tr>
			<th>type</th>
			<th><abbr title="HP">H</abbr></th>
			<th><abbr title="Range">R</abbr></th>
			<th><abbr title="Rate">R</abbr></th>
			<th><abbr title="Damage">D</abbr></th>
			<th><abbr title="Targets">T</abbr></th>
			<th><abbr title="Splash">S</abbr></th>
			<th><abbr title="Concussion">C</abbr></th>
			<th><abbr title="Defense">D</abbr></th>
			<th>level</th>
			<th>healh</th>
			<th>used</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($results as $row ): ?>
		<tr data-id="<?php echo $row->id ?>" data-type="<?php echo $row->type ?>" data-level="<?php echo $row->level ?>" data-durability="<?php echo $row->durability ?>" data-equipped="<?php echo $row->equipped ? 1 : 0 ?>" data-hp="<?php echo $bonus[$row->type]['hp']?>" data-range="<?php echo $bonus[$row->type]['range']?>" data-rate="<?php echo $bonus[$row->type]['rate']?>" data-damage="<?php echo $bonus[$row->type]['damage']?>" data-targets="<?php echo $bonus[$row->type]['targets']?>" data-splash="<?php echo $bonus[$row->type]['splash']?>" data-concussion="<?php echo $bonus[$row->type]['concussion']?>" title="<?php echo $row->id ?>">
			<td><?php echo link_to(__(strtolower($row->type).'.name', array(), 'ew-items'), "equipment-info/{$row->type}") ?></td>
			<?php foreach(array('hp', 'range', 'rate', 'damage', 'targets', 'splash', 'concussion', 'defense') as $k) : ?>
			<td><?php if($bonus[$row->type][$k]) : ?>&plus;<?php else : ?>&minus;<?php endif ?></td>
			<?php endforeach ?>
			<td class="cell-level"><?php echo $row->level ?></td>
			<td class="cell-durability"><?php if($row->durability) : ?><?php printf('%d%%', $row->durability / 10) ?><?php else : ?>&mdash;<?php endif ?></td>
			<td><?php echo $row->equipped ? '&plus;' : '&minus;' ?></td>
			<td>
				<a href="#" class="btn btn-mini action-trash"><i class="icon-trash"></i></a>
				<a href="#" class="btn btn-mini action-upgrade"><i class="icon-arrow-up"></i></a>
			</td>
		</tr>
		<?php endforeach ?>
	</tbody>
	<tfoot>
	<tr>
	<th colspan="11"><span id="equipment-displayed"><?php echo count($results)?></span> / <span id="equipment-total"><?php echo count($results)?></span></th>
	</tr>
	</tfoot>
</table>

<div>
	<button id="action-upgrade-all" class="btn"><i class="icon-arrow-up"></i> <?php echo __('Upgrade all')?></button>
	<button id="action-delete-all" class="btn"><i class="icon-trash"></i> <?php echo __('Delete all')?></button>
</div>

<ul id="equipment-log" style="overflow: auto; max-height: 400px;">
</ul>

<script type="text/javascript">

$(function(){
//	var levels = <?php //echo json_encode($levels->getRawValue())?>;
	
	$('#filter-type, #filter-level, #filter-durability, #filter-equipped, #filter-bonus').change(function(){
		$('#equipment-list > tbody').trigger('update-view');
	});

	$('#equipment-list > tbody').on('update-view', function(){		
		$(this).children('tr').sortElements(function(a, b){
			var c = $(a).data('level');
			var d = $(b).data('level');
			return (c == d) ? 0 : ( c > d ? 1 : -1 );
		});

		$(this).children('tr').each(function(){
			var v = true;
			if($('#filter-type').val() && ($('#filter-type').val().indexOf($(this).data('type')) == -1)) { 
				v = false;
			}
			if($('#filter-level').val() && ($('#filter-level').val().indexOf(String($(this).data('level'))) == -1)) {
				v = false;
			}
			var b = $('#filter-bonus').val();
			if(b) {
				for(var i in b) {
					if($(this).data(b[i])) {
						v = false;
						break;
					}
				}
			}

			switch($('#filter-durability').val()) {
			case '0':
				if($(this).data('durability') != 0) v = false;
				break;
			case '1':
				if($(this).data('durability') == 0) v = false;
				break;				
			}

			switch($('#filter-equipped').val()) {
			case '0':
				if($(this).data('equipped')) v = false;
				break;
			case '1':
				if(!$(this).data('equipped')) v = false;
				break;
			}

			$(this).toggle(v);

			$(this).find('a.action-upgrade').toggle($(this).data('durability') > 0);
			
		});

		$('#equipment-displayed').text($('#equipment-list > tbody > tr:visible').size());

	}).trigger('update-view');

	$('#action-delete-all').click(function(){
		var set = $('#equipment-list > tbody > tr:visible');
		$.ajax({
			url: '<?php echo url_for('equipment/destroy')?>',
			data: {
				multi: 1,
				ids: set.map(function(){
					return $(this).data('id');
				}).get()
			},
			success: function(data){
				bootbox.alert('done');
			},
			error: function(){
				bootbox.alert('error');				
			}
		});
		return false;
	});

	$('#action-upgrade-all').click(function(){

		function upgradeFirst()
		{
			var set = $('#equipment-list > tbody > tr:visible');

			for(var i = 0; i < set.length; i++)
			{
				if(set.eq(i).data('durability') != 0)
				{
					upgradeNode(set.eq(i), function(){
						setTimeout(upgradeFirst, 1000);
					});
					return;
				}
			}
			bootbox.alert('UPGRADE FINISHED');
		}

		upgradeFirst(); 
		return false;
	});

	function upgradeNode(node, readyCallback) {

		if(node.data('durability') == 0)
		{
			LOG('WTF???');
			return;
		}
		
		$.ajax({
			url: '<?php echo url_for('equipment/upgrade')?>',
			data: {
				id: node.data('id')
			},
			success: function(data){
				if(data)
				{
					LOG(node.data('id') + ' success', 'text-success');
					var l = node.data('level') + 1;
					node.data('level', l);
					node.children('td.cell-level').text(l);
					node.addClass('success').removeClass('error');
				}
				else
				{
					LOG(node.data('id') + ' failed', 'text-error');
					node.data('durability', 0);
					node.children('.cell-durability').html('&mdash;');
					node.addClass('error').removeClass('success');
				}

				$('#equipment-list > tbody').trigger('update-view');
								
				if(readyCallback) readyCallback();				
			},
			error: function(){
				LOG('AJAX-ERROR', 'error');
			}
		});
	}

	$('.action-upgrade').click(function(){
		upgradeNode($(this).closest('tr'));
		return false;
	});

});

function LOG(message, decoration) {
	console.log(message);
	var record = $('<li>').html(message);
	if(decoration) record.addClass(decoration);	
	$('#equipment-log').append(record);
}
</script>
