<?php use_helper('edgeworld') ?>
<?php use_javascript('jquery.lazyload.min.js') ?>
<?php use_javascript('jquery.scrollstop.js') ?>

<div class="page-header">
  <h1>Токены</h1>
</div>

<div class="row" id="lazy-images-container">
  <?php foreach ($tokens as $token) : ?>
    <div class="col-md-3">
      <div class="panel panel-default">
        <div class="panel-heading oneline-title">
          <h3 class="panel-title">
            <a href="<?php echo url_for('@manifest-token?type=' . $token['type']) ?>"><?php echo __EW('items', $token['type'], 'name') ?></a>
          </h3>
        </div>
        <div class="panel-body text-center edgeworld-bg" style="height: 140px;">
          <a href="<?php echo url_for('@manifest-token?type=' . $token['type']) ?>">
            <img alt="<?php echo $token['type'] ?>" style="height: 120px;" src="<?php echo image_path('loading.gif') ?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($token['type'])) ?>">
          </a>
        </div>
      </div>

    </div>
  <?php endforeach; ?>
</div>

<script type="text/javascript">
  $(function() {
    $('#lazy-images-container img').lazyload({
      event: "scrollstop"
    });
  });
</script>
