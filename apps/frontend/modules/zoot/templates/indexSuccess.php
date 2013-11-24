<?php use_helper('edgeworld') ?>
<div class="page-header">
  <h1>Zoot</h1>
  <p class="lead"><?php echo __EW('zoots', 'welcome') ?></p>
  <button class="btn btn-danger btn-lg" id="play"><i class="glyphicon glyphicon-play"></i> Play!</button>
  <div class="progress progress-striped active" id="processing" style="display: none;">
    <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
      <span class="sr-only">Processing...</span>
    </div>
  </div>
</div>

<p id="no-more-tokens" class="alert alert-danger"><?php echo __EW('zoots', 'need.tokens') ?></p>

<div class="row" id="prizes">
</div>

<script type="text/javascript">
  var tokens = <?php echo $tokens ?>;
  
  $('#play').toggle(tokens > 0);
  $('#no-more-tokens').toggle(tokens == 0);

  $('#play').click(function() {
    $(this).hide();
    $('#processing').show();
    
    lottery();
    
    function lottery() {
      if (tokens > 0) {
        $.post('<?php echo url_for('zoot/lottery') ?>', function(response) {
          if (response.success) {
            tokens = response.tokens;
            $(response.prize).appendTo('#prizes');
            $('#no-more-tokens').toggle(tokens == 0);
            $('#processing').toggle(tokens > 0);

            lottery();
          }
          else {
            alert(response.error);
          }
        });
      }
    }
  });
</script>
