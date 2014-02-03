<h1>Карта №<?php echo $map->id ?></h1>


<button id="action-update" class="btn btn-default"><i class="glyphicon glyphicon-refresh"></i> Обновить</button>
<div class="progress" style="display: none;">
  <div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"></div>
</div>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Альянс</th>
      <th>Базы</th>
      <th>Очки</th>
    </tr>
  </thead>
  <?php foreach ($alliances_stat as $stat) : ?>
    <tr>
      <td><?php echo isset($alliance_lookup[$stat['collection_id']]) ? $alliance_lookup[$stat['collection_id']]['name'] : "/ NOT FOUND /" ?> &mdash; <?php echo $stat['collection_id'] ?></td>
      <td><?php echo $stat['nodes'] ?></td>
      <td><?php echo $stat['score'] ?></td>
    </tr>
  <?php endforeach ?>
</table>

<script type="text/javascript">
  var cx = <?php echo $map->width / $map->chunk_size ?>;
  var cy = <?php echo $map->height / $map->chunk_size ?>;
  var cs = <?php echo $map->chunk_size ?>;
  $('#action-update').click(function() {
    $(this).hide();
    $('.progress').show();

    var thread = 0;
    var updated = 0;

    for (var y = 0; y < cy; y++) {
      thread++;
      update(0, y);
    }

    function update(x, y) {
      if (x >= cx) {
        thread--;
        if (thread == 0) {
          window.location.reload();
        }
        return;
      }
      updated++;
      $('.progress-bar').css('width', updated * 100 / (cx * cy) + '%');

      $.get('<?php echo url_for('@map-update') ?>', {
        id: <?php echo $map->id ?>,
        x: x,
        y: y
      }, function(response) {
        update(x + 1, y);
      });
    }
  });
</script>