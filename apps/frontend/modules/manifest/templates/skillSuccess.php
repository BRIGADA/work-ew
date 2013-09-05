<?php use_helper('I18N')?>
<?php use_helper('highcharts')?>

<div class="page-header">
	<h1><?php echo __(sprintf('skills.%s.name', strtolower($skill->type)), array(), 'ew-hero') ?></h1>
	<p class="lead"><?php echo __(sprintf('skills.%s.description', strtolower($skill->type)), array(), 'ew-hero') ?></p>
</div>

<?php $chart_categories = array(); foreach ($skill->levels as $level) $chart_categories[] = sprintf('L%d', $level->level);?>
<div class="row">
	<div class="span12">
		<h3>Требования</h3>
		<?php echo Highcharts('SP', array('SP'=>$skill->requirementsSP()), array('xAxis'=>array('categories'=>$chart_categories)))?>
	</div>
	<div class="span12">
		<h3>Характеристики</h3>
		<?php foreach($skill->stats() as $stat) : ?>
		<?php echo Highcharts($stat, array($stat=>$skill->stat($stat)), array('xAxis'=>array('categories'=>$chart_categories)))?>
		<?php endforeach ?>
	</div>
</div>
