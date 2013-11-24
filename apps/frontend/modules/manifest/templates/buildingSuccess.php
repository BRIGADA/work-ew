<?php use_helper('highcharts') ?>
<?php use_helper('edgeworld') ?>
<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header clearfix">
  <img class="pull-right" src="<?php echo image_path('http://kabam1-a.akamaihd.net/edgeworld/images/buildings/' . strtolower($building->type) . '_3.png') ?>"/>
  <h1><?php echo __EW('buildings', $building->type, 'name') ?></h1>
  <p class="lead">
    <?php echo __EW('buildings', $building->type, 'description') ?>
  </p>
  <p class="lead">
  <span class="label label-default"><?php echo $building->size_x ?>x<?php echo $building->size_y ?></span>
  </p>

</div>

<?php
$ll = array_map(function($l) {
  return "L{$l['level']}";
}, (array) $building->levels->toArray()->getRawValue())
?>

<div id="upgrade-time" class="thumbnail"></div>

<p><strong>Общее время постройки:</strong> <?php echo seconds2times(array_sum($building->getDataSeries('time')->getRawValue())) ?></p>

<div id="requirements-resources" class="thumbnail"></div>

<?php foreach ($stats as $stat) : ?>
<div id="stat-<?php echo $stat ?>" class="thumbnail"></div>
<?php endforeach ?>

<?php
echo _highcharts(array(
    'credits' => ['enabled' => false],
    'legend' => ['enabled' => false],
    'title' => ['text' => 'time'],
    'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
    'yAxis' => ['min' => 0],
    'series' => [
        ['data' => $building->getDataSeries('time')->getRawValue()],
    ]), '#upgrade-time');
?>
<?php
echo _highcharts(array(
    'credits' => ['enabled' => false],
    'title' => ['text' => 'resources'],
    'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
    'yAxis' => ['min' => 0],
    'series' => [
        ['data' => $building->getDataSeries('requirements/resources/gas')->getRawValue(), 'name' => 'gas'],
        ['data' => $building->getDataSeries('requirements/resources/crystal')->getRawValue(), 'name' => 'crystal'],
        ['data' => $building->getDataSeries('requirements/resources/energy')->getRawValue(), 'name' => 'energy'],
        ['data' => $building->getDataSeries('requirements/resources/uranium')->getRawValue(), 'name' => 'uranium'],
    ]), '#requirements-resources');
?>
<?php foreach ($stats as $stat) : ?>
  <?php
  echo _highcharts(array(
      'credits' => ['enabled' => false],
      'legend' => ['enabled' => false],
      'title' => ['text' => $stat],
      'yAxis' => ['title' => ['text' => false], 'min' => 0],
      'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
      'series' => [
          ['data' => $building->getDataSeries("stats/{$stat}")->getRawValue()]
      ]), "#stat-{$stat}")
  ?>
<?php endforeach ?>
