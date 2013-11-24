<?php use_helper('edgeworld') ?>
<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header">
  <h1><?php echo __EW('items', $equipment->type, 'name') ?></h1>
</div>

<div id="chart-chance" style="min-height: 300px"></div>

<h3>Требования</h3>
<div id="chart-time" style="min-height: 300px"></div>
<?php foreach ($resources as $res) : ?>
  <br>
  <div id="chart-resources-<?php echo $res ?>" style="min-height: 300px"></div>
<?php endforeach ?>

<div class="row">
  <div class="span12">
    <h3>Характеристики</h3>
  </div>
  <?php foreach ($stats as $stat) : ?>
    <div class="span12" style="padding-bottom: 20px;">
      <?php if (!in_array($stat, $nonnumeric->getRawValue())) : ?>
        <div id="chart-stats-<?php echo $stat ?>" style="min-height: 300px"></div>
      <?php else : ?>
        <table class="table table-condensed table-bordered">
          <tr>
            <th></th><?php foreach ($levels as $level) : ?><th><?php echo "L{$level->level}" ?></th><?php endforeach ?>
          </tr>
          <tr>
            <th><?php echo $stat ?></th>
            <?php foreach ($levels as $level) : ?>
              <td><?php echo $level->stats[$stat] ?></td>
            <?php endforeach ?>
          </tr>
        </table>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</div>

<script type="text/javascript">
  var levelLabels = [<?php foreach ($levels as $level) : ?><?php echo json_encode(sprintf('L%d', $level->level)) ?>, <?php endforeach ?>];

  $('#chart-chance').highcharts({
    chart: {
      borderWidth: 1
    },
    title: {
      text: 'Шанс успешного обновления'
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
      title: {text: '%'}
    },
    series: [{
        data: [<?php foreach ($levels as $level) : ?><?php echo $level->upgrade_chance * 10 ?>,<?php endforeach ?>]
      }]
  });
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
      title: {text: 'Секунды'}
    },
    tooltip: {
      formatter: function() {
        var value = this.y;
        var result = '';
        if (value > 60) {
          if (value > 3600) {
            var hours = Math.floor(value / 3600);
            value = value % 3600;
            if (hours < 10)
              result += '0';
            result += hours.toString();
            result += ':';
          }
          var minutes = Math.floor(value / 60);
          value = value % 60;
          if (minutes < 10)
            result += '0';
          result += minutes.toString();
          result += ':';
        }

        if (value < 10)
          result += '0';
        result += value.toString();

        return result;
      },
    },
    series: [{
        data: [<?php foreach ($levels as $level) : ?><?php echo $level->time ?>,<?php endforeach ?>]
      }],
  });

<?php foreach ($resources as $res) : ?>
    $('#chart-resources-<?php echo $res ?>').highcharts({
      chart: {
        borderWidth: 1
      },
      title: {
        text: '<?php echo $res ?>'
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
        title: {text: null}
      },
      series: [{
          data: [<?php foreach ($levels as $level) : ?><?php echo $level->requirements['resources'][$res] ?>,<?php endforeach ?>]
        }],
    });
<?php endforeach ?>

<?php foreach ($stats as $stat) : ?>
    $('#chart-stats-<?php echo $stat ?>').highcharts({
      chart: {
        borderWidth: 1
      },
      title: {
        text: '<?php echo $stat ?>'
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
        title: {text: null}
      },
      series: [{
          data: [<?php foreach ($levels as $level) : ?><?php echo $level->stats[$stat] ?>,<?php endforeach ?>]
        }]
    });
<?php endforeach ?>
</script>

