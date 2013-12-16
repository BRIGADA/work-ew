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
    return "L{$l}";
}, $building->getAllValues('level')->getRawValue())
?>
<h2>Время</h2>
<div id="time-chart"></div>
<p><strong>Общее время постройки:</strong> <?php echo seconds2times(array_sum($building->getAllValues('time')->getRawValue())) ?></p>
<?php
echo _highcharts(array(
    'credits' => ['enabled' => false],
    'legend' => ['enabled' => false],
    'title' => ['text' => NULL],
    'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
    'yAxis' => ['min' => 0, 'title' => ['text' => 'Секунды']],
    'series' => [
        ['data' => $building->getAllValues('time')->getRawValue()],
    ]), '#time-chart');
?>

<h2>Требования</h2>
<?php foreach ($building->getAllKeys('requirements') as $requirement) : ?>
    <?php if ($requirement == 'items') : ?>
        <h3>Предметы</h3>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Предмет</th>
                    <?php foreach ($ll as $level) : ?>
                        <th style="width: 40px;"><?php echo $level ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($building->getAllKeys('requirements/items') as $item) : ?>
                    <tr>
                        <td><a href="<?php echo url_for("@manifest-item?type={$item}") ?>"><?php echo __EW('items', $item, 'name') ?></a></td>
                        <?php foreach ($building->getAllValues("requirements/items/{$item}") as $quantity) : ?>
                            <td style="text-align: center"><?php echo $quantity ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php elseif ($requirement == 'building_limits') : ?>
        <h3>Ограничения на постройку</h3>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Здание</th>
                    <?php foreach ($ll as $level) : ?>
                        <th style="width: 40px;"><?php echo $level ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($building->getAllKeys('requirements/building_limits') as $type) : ?>
                    <tr>
                        <td><a href="<?php echo url_for("@manifest-building?type={$type}") ?>"><?php echo __EW('buildings', $type, 'name') ?></a></td>
                        <?php foreach ($building->getAllValues("requirements/building_limits/{$type}") as $quantity) : ?>
                            <td style="text-align: center"><?php echo $quantity ? $quantity : '' ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php elseif ($requirement == 'resources') : ?>
        <h3>Ресурсы</h3>
        <div id="chart-resources"></div>
        <?php
        echo _highcharts(array(
            'credits' => ['enabled' => false],
            'title' => ['text' => null],
            'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
            'yAxis' => ['min' => 0, 'title' => ['text' => NULL]],
            'series' => array_map(function($type) use (&$building) {
                return array('data' => $building->getAllValues("requirements/resources/{$type}")->getRawValue(), 'name' => $type);
            }, $building->getAllKeys('requirements/resources')->getRawValue()
            )), '#chart-resources');
        ?>
    <?php elseif ($requirement == 'buildings') : ?>
        <h3>Здания</h3>
        <table class="table table-striped table-bordered table-condensed">
            <thead>
                <tr>
                    <th>Здание</th>
                    <?php foreach ($ll as $level) : ?>
                        <th style="width: 40px;"><?php echo $level ?></th>
                    <?php endforeach ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($building->getAllKeys('requirements/buildings') as $type) : ?>
                    <tr>
                        <td><a href="<?php echo url_for("@manifest-building?type={$type}") ?>"><?php echo __EW('buildings', $type, 'name') ?></a></td>
                        <?php foreach ($building->getAllValues("requirements/buildings/{$type}") as $quantity) : ?>
                            <td style="text-align: center"><?php echo $quantity ? $quantity : '' ?></td>
                        <?php endforeach ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>

    <?php else : ?>
        <div class="alert alert-danger"><strong><?php echo $requirement ?></strong> &mdash; не знаю как показать!</div>
    <?php endif ?>

<?php endforeach ?>

<h2>Статистика</h2>

<?php foreach ($building->getAllKeys('stats') as $stat) : ?>
    <?php $values = $building->getAllValues("stats/{$stat}")->getRawValue() ?>
    <?php if (serie_is_const($values)) : ?>
        <?php $value = serie_first_value($values) ?>
        <h3><?php echo $stat ?>: <?php echo is_bool($value) ? ($value ? 'ДА' : 'НЕТ') : $value ?></h3>
    <?php else : ?>
        <h3><?php echo $stat ?></h3>
        <?php if (serie_is_numeric($values)) : ?>
            <div id="stat-<?php echo $stat ?>-chart"></div>
            <?php
            echo _highcharts(array(
                'credits' => ['enabled' => false],
                'legend' => ['enabled' => false],
                'title' => ['text' => NULL],
                'yAxis' => ['title' => ['text' => NULL]],
                'xAxis' => ['categories' => $ll, 'gridLineWidth' => 1],
                'series' => [
                    ['data' => $values]
                ]), "#stat-{$stat}-chart")
            ?>
        <?php elseif (serie_is_boolean($values)) : ?>
            <table class="table table-bordered table-condensed text-center">
                <tr>
                    <?php foreach ($ll as $level) : ?>
                        <th style="width: 50px;"><?php echo $level ?></th>
                    <?php endforeach ?>
                </tr>
                <tr>
                    <?php foreach ($values as $value) : ?>
                        <td><?php echo!is_null($value) ? ($value ? '&plus;' : '&minus;') : '' ?></td>
                    <?php endforeach ?>
                </tr>
            </table>
        <?php else : ?>
            <div class="alert alert-danger">Не знаю как отобразить</div>
            <?php var_dump($values) ?>
        <?php endif ?>
    <?php endif ?>
<?php endforeach ?>
