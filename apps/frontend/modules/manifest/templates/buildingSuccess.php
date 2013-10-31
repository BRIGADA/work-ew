<?php use_helper('highcharts') ?>
<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header">
    <h1><?php echo $building->type ?></h1>
    <p class="lead"><?php echo $building->size_x ?>x<?php echo $building->size_y ?></p>
</div>
<div class="row">
    <?php
    $ll = array_map(function($l) {
        return "L{$l['level']}";
    }, (array) $building->levels->toArray()->getRawValue())
    ?>
    <ul class="thumbnails">
        <li class="span12">
            <div class="thumbnail" id="requirements-resources"></div>
        </li>
        <?php foreach ($stats as $stat) : ?>
            <li class="span6">
                <div class="thumbnail" id="stat-<?php echo $stat ?>"></div>
            </li>
        <?php endforeach ?>
    </ul>
</div>
<?php
echo _highcharts(array(
    'credits' => ['enabled' => false],
    'title' => ['text' => 'resources'],
    'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
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
        'legend' => ['enabled'=>false],
        'title' => ['text' => $stat],
        'yAxis'=>['title'=>['text'=>false]],
        'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
        'series' => [
            ['data'=>$building->getDataSeries("stats/{$stat}")->getRawValue()]
        ]), "#stat-{$stat}")
    ?>
<?php endforeach ?>

