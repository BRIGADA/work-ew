<?php use_helper('I18N')?>
<?php use_javascript('http://code.highcharts.com/highcharts.js')?>

<div class="page-header clearfix">
	<img alt="<?php echo $unit->type ?>"
		src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/units/%s.png', strtolower($unit->type))?>"
		class="pull-right" style="height: 200px">
	<h1><?php echo __(strtolower($unit->type.'.name'), array(), 'ew-units') ?></h1>
	<p class="lead"><?php echo __(strtolower($unit->type.'.description'), array(), 'ew-units') ?></p>
	<div>
		<a href="<?php echo url_for('@manifest-units')?>" class="btn btn-large"><i
			class="glyphicon glyphicon-th"></i> Назад</a>
	</div>
</div>

<div class="row">
	<div class="span12">
		<h3>Требования</h3>
		<div id="chart-time" style="min-height: 400px"></div>
		<br>
		<div id="chart-resources" style="height: 400px;"></div>
		<?php if(count($buildings) || count($research)) : ?>
		<br>
		<div id="chart-requirements" style="height: 400px;"></div>
		<?php endif ?>
		<?php if(count($items)) : ?>
		<br>
		<div id="chart-items" style="height: 200px;"></div>
		<?php endif ?>
	</div>
	<div class="span12">
		<h3>Показатели</h3>
	</div>
		<?php foreach($stats as $stat) : ?>
	<div id="chart-stat-<?php echo $stat ?>" class="span6" style="height: 300px; padding-bottom: 10px;"></div>
		<?php endforeach ?>
</div>

<script type="text/javascript">
$(function(){
	var levelLabels = [<?php foreach ($unit->levels as $level ) : ?><?php echo json_encode(sprintf('L%d', $level->level )) ?>, <?php endforeach ?>];
	
	$('#chart-time').highcharts({
		chart: {
			borderWidth: 1
		},
		title: {
			text: 'Время'
		},
		legend: {
			enabled: false
		},
		xAxis: {
			categories: levelLabels,
			gridLineWidth: 1
		},
		yAxis: {
			min: 0,
			
			title: {
				text: 'Секунды'
			}
		},
		tooltip: {
			formatter: function(){
				var value = this.y;
				var result = '';
				if(value > 60) {
					if(value > 3600) {
						var hours = Math.floor(value / 3600);
						value = value % 3600;
						if(hours < 10) result += '0';
						result += hours.toString();
						result += ':';
					}
					var minutes = Math.floor(value / 60);
					value = value % 60;
					if(minutes < 10) result += '0';
					result += minutes.toString();
					result += ':';
				}

				if(value < 10) result += '0';
				result += value.toString();
				
				return result;
			},
		},
		series: [{
			data: [<?php foreach ($unit->levels as $level) : ?><?php echo $level->time ?>,<?php endforeach ?>]
		}],
	});
	
	$('#chart-resources').highcharts({
		chart: {
			borderWidth: 1,
		},
		title: {
			text: 'Ресурсы',
		},
	  colors: ['#1aadce', '#a966c4', '#e8e823', '#47de0b'],
    xAxis: {
      categories: levelLabels,
			gridLineWidth: 1,	      
    },
    yAxis: {
      title: {
	      text: 'Количество',
      },
    	min: 0,
    },
    legend: {
      layout: 'vertical',
      align: 'right',
      verticalAlign: 'middle',
      borderWidth: 0,
    },
    series: [<?php foreach(array('crystal', 'gas', 'energy', 'uranium') as $r) :?>{
      name: '<?php echo $r?>',
      data: [<?php foreach($unit->levels as $level) : ?><?php echo $level->requirements['resources'][$r] ?>,<?php endforeach ?>],
    },<?php endforeach ?>],
  });

	$('#chart-requirements').highcharts({
		chart: {
			borderWidth: 1,
			type: 'column',
		},
		title: {
			text: 'Здания и исследования',
		},
		xAxis: {
			categories: levelLabels,
			gridLineWidth: 1,			
		},
		yAxis: {
			title: {
				text: 'Уровень',
			},
			min: 0,
			max: 16,
			minorGridLineColor: '#e0e0e0',
			minorGridLineWidth: 1,
			minorTickInterval: 1,
		},
		series: [
	 		<?php foreach ($buildings as $b) : ?>
	 		{
		 		name: '<?php echo $b?>',
		 		data: [ <?php foreach($unit->levels as $l ) : ?><?php echo (isset($l->requirements['buildings']) && isset($l->requirements['buildings'][$b])) ? (integer)$l->requirements['buildings'][$b] : 0 ?>,<?php endforeach ?>],
	 		},
	 		<?php endforeach ?>
	 		<?php foreach ($research as $r) : ?>
	 		{
		 		name: '<?php echo $r ?>',
		 		data: [ <?php foreach($unit->levels as $l ) : ?><?php echo (integer)$l->requirements['research'][$r]?>,<?php endforeach ?>],
	 		},
	 		<?php endforeach ?>],
	});

	<?php if(count($items)) : ?>
	$('#chart-items').highcharts({
		chart: {
			borderWidth: 1,
			type: 'column',
		},
		title: {
			text: 'Предметы',
		},
		xAxis: {
			categories: levelLabels,
			gridLineWidth: 1,			
		},
		yAxis: {
			title: {
				text: 'Уровень',
			},
			min: 0,
		},
		series: [
		<?php foreach ($items as $item) : ?>
		{
			name: '<?php echo $item ?>',
			data: [<?php foreach($unit->levels as $l):?><?php echo $l->requirements['items'][$item] ?>, <?php endforeach ?>],
		}
		<?php endforeach ?>
		],
	});
	<?php endif ?>
	
	<?php foreach ($stats as $stat) : ?>
	$('#chart-stat-<?php echo $stat?>').highcharts({
		chart: {
			borderWidth: 1,
		},
		title: {
			text: '<?php echo $stat ?>',
		},
		legend: {
			enabled: false
		},
		xAxis: {
			categories: levelLabels,
			gridLineWidth: 1,
		},
		yAxis: {
			min: 0,
		},
		series: [{
			name: '<?php echo $stat ?>',
			data: [<?php foreach ($unit->levels as $l) : ?><?php echo $l->stats[$stat] ?>,<?php endforeach ?>],
		}],		
	});
	<?php endforeach ?>
});
</script>
