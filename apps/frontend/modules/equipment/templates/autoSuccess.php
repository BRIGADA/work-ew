<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header clearfix" style="margin-top: 0px; margin-bottom: 15px;">
    <h1>Automatic Equipment Upgrader<button id="action-start" class="btn btn-large btn-primary pull-right">Запуск</button><button id="action-stop" class="btn btn-large btn-primary pull-right hide">Стоп</button></h1>
</div>

<!--
<div class="progress" style="height: 19px;"><div class="bar" style="width: 33%;"></div></div>
-->

<div class="row" id="charts">
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
    <div class="col-lg-4" data-fail="0" data-done="0">
	<div class="thumbnail" style="height: 340px;"></div>
    </div>
</div>

<script type="text/javascript">
    var current = <?php echo is_null($current) ? 'null' : json_encode($current->getRawValue()) ?>;

    updateCharts();

    $('#action-stop').toggle(current instanceof Object);
    $('#action-start').toggle(!(current instanceof Object)).click(function(){
        $.get('<?php echo url_for('common/clientProxy')?>', {
            query: {
                cmd: 'autoequipment_start',
                user_id: <?php echo $sf_user->getAttribute('user_id', null, 'player/data')?>
                
            },
            type: 'application/json'
        }, function(response){
            
        });
    });

    function updateCharts() {
        if (!(current instanceof Object)) {
            console.log('current not object');
            return;
        }

        $('#charts > div').data('done', 0).data('fail', 0);

        for (var i in current) {
            var c = $('#charts > div').eq(parseInt(i) > 5 ? 5 : (parseInt(i) - 1));
            c.data('done', c.data('done') + current[i].done);
            c.data('fail', c.data('fail') + current[i].fail);
        }

        $('#charts > div').each(function() {
            $(this).children('div').highcharts({
                chart: {
                    animation: false,
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false
                },
                tooltip: {
                    pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y})'
                },
                credits: {
                    enabled: false
                },
                title: {
                    text: null
                },
                plotOptions: {
                    pie: {
                        animation: false,
                        dataLabels: {
                            enabled: false,
//                                                        format: '{point.y}'
                        }
                    }
                },
                series: [{
                        type: 'pie',
                        data: [['Успех', $(this).data('done')], ['Отказ', $(this).data('fail')]]
                    }]
            });
        });
    }
    
    var interval = setInterval(function(){
        $.ajax({
            url: '<?php echo url_for('equipment/auto') ?>',
            success: function(response) {
                current = response;
                updateCharts();
            },
            error: function(){
                clearInterval(interval);
                bootbox.alert('failed to get actual data');
            }
        });
    }, 1000 * 30);
</script>
