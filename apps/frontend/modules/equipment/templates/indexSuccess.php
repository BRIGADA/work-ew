<?php use_helper('I18N')?>
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
		<tr data-id="<?php echo $row->id ?>" data-type="<?php echo $row->type ?>" data-level="<?php echo $row->level ?>" data-durability="<?php echo $row->durability ?>" data-equipped="<?php echo $row->equipped ? 1 : 0 ?>" data-hp="<?php echo $bonus[$row->type]['hp']?>" data-range="<?php echo $bonus[$row->type]['range']?>" data-rate="<?php echo $bonus[$row->type]['rate']?>" data-damage="<?php echo $bonus[$row->type]['damage']?>" data-targets="<?php echo $bonus[$row->type]['targets']?>" data-splash="<?php echo $bonus[$row->type]['splash']?>" data-concussion="<?php echo $bonus[$row->type]['concussion']?>">
			<td><?php echo link_to(__(strtolower($row->type).'.name', array(), 'ew-items'), "equipment-info/{$row->type}") ?></td>
			<?php foreach(array('hp', 'range', 'rate', 'damage', 'targets', 'splash', 'concussion', 'defense') as $k) : ?>
			<td><?php if($bonus[$row->type][$k]) : ?>&plus;<?php else : ?>&minus;<?php endif ?></td>
			<?php endforeach ?>
			<td><?php echo $row->level ?></td>
			<td><?php if($row->durability) : ?><?php printf('%d%%', $row->durability / 10) ?><?php else : ?>&mdash;<?php endif ?></td>
			<td><?php echo $row->equipped ? '&plus;' : '&minus;' ?></td>
			<td><a href="#" class="btn btn-mini action-upgrade"><i class="icon-circle-arrow-up"></i></a></td>
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
	<button id="action-upgrade-all" class="btn"><i class="icon-circle-arrow-up"></i> <?php echo __('Upgrade')?></button>
	<button id="action-delete-all" class="btn"><i class="icon-trash"></i> <?php echo __('Delete')?></button>
</div>

<ul id="equipment-log">
</ul>

<script type="text/javascript">

$(function(){
//	var levels = <?php //echo json_encode($levels->getRawValue())?>;
	
	$('#filter-type, #filter-level, #filter-durability, #filter-equipped, #filter-bonus').change(function(){
		$('#equipment-list > tbody').trigger('filter');
	});

	$('#equipment-list > tbody').on('filter', function(){
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
			
		});

		$('#equipment-displayed').text($('#equipment-list > tbody > tr:visible').size());

	});

	$('#action-upgrade-all').click(function(){
		$('#equipment-list > tbody > tr:visible').each(function(){
			LOG($(this).data('id'));
		});
	});

	$('.action-upgrade').click(function(){
		$.ajax({
			url: '<?php echo url_for('equipment/upgrade') ?>',
			data: {
				id: $(this).closest('tr').data('id')
			},
			success: function(response) {
				LOG('SUCCESS');
			},
			error: function() {
				LOG('ERROR');
			}
		});
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
