<?php use_helper('highcharts')?>

<div class="page-header">
<h1><?php echo $research->type ?></h1>
</div>

<?php $level_labels = array(); foreach ($research->levels as $level) $level_lavels[] = sprintf('L%d', $level->level)?>
<div class="row">
	<div class="span12">
		<?php echo Highcharts('Время', array('time'=>$research->time), array('xAxis'=>array('categories'=>$level_lavels)))?>
	</div>
	<div class="span12">
		<h3>Потребности в ресурсах</h3>
		<?php echo Highcharts(null, $chart_resources, array('xAxis'=>array('categories'=>$level_lavels), 'yAxis'=>array('min'=>0)))?>		
	</div>
	<?php if(count($requirements_buildings)) : ?>
	<div class="span12">
		<h3>Необходимые постройки</h3>
		<?php echo Highcharts(null, $requirements_buildings, array('xAxis'=>array('categories'=>$level_lavels), 'yAxis'=>array('min'=>0)))?>
	</div>
	<?php endif ?>
	<?php if(count($requirements_items)) : ?>
	<div class="span12">
		<h3>Необходимые предметы</h3>
		<?php echo Highcharts(null, $requirements_items, array('chart'=>array('type'=>'column'),'xAxis'=>array('categories'=>$level_lavels), 'yAxis'=>array('min'=>0)))?>
	</div>
	<?php endif ?>
</div>
