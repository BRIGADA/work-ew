<?php use_helper('I18N')?>
<?php use_helper('highcharts')?>

<div class="page-header">
	<h1>Статистика по юнитам</h1>
	<p class="lead">
		<a href="<?php echo url_for('@manifest-units') ?>" class="btn"><i class="glyphicon glyphicon-list"></i> Список</a>
	</p>
</div>
<div class="row">
	<div class="span12">
		<?php echo Highcharts("Время", $chart_time, array('yAxis'=>array('min'=>0), 'legend'=>array('layout'=>'vertical', 'align'=>'right'))) ?>
		<?php echo Highcharts("Уран", $chart_uranium, array('yAxis'=>array('min'=>0), 'legend'=>array('layout'=>'vertical', 'align'=>'right'))) ?>
	</div>
</div>
