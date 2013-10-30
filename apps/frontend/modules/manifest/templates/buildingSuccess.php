<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header">
    <h1><?php echo $building->type ?></h1>
    <p class="lead"><?php echo $building->size_x ?>x<?php echo $building->size_y ?></p>
</div>
<div class="row">
    <ul class="thumbnails">
        <?php foreach ($stats as $stat) : ?>
            <li class="span6">
                <div class="thumbnail" id="stat-<?php echo $stat ?>"></div>
                <script type="text/javascript">
                    $('#stat-<?php echo $stat ?>').highcharts({
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '<?php echo $stat ?>'
                        },
                        legend: {enabled: false},
                        xAxis: {
                            categories: [<?php foreach ($building->levels as $level) : ?><?php echo $level->level ?>,<?php endforeach ?>],
                            gridLineWidth: 1
                        },
                        yAxis: {title: {text: false}},
                        series: [{
                                data: <?php echo json_encode($building->getStat($stat)->getRawValue(), JSON_NUMERIC_CHECK) ?>
                            }]
                    });
                </script>
            </li>
        <?php endforeach ?>
    </ul>
</div>
