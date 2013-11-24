<?php use_helper('edgeworld') ?>
<div class="col-md-3 col-lg-3">
  <div class="panel panel-default">
    <div class="panel-heading oneline-title">
      <h3 class="panel-title" title="<?php echo __EW('items', $type, 'name') ?>">
        <a href="<?php echo url_for('@manifest-item?type=' . $type) ?>"><?php echo __EW('items', $type, 'name') ?></a>
      </h3>
    </div>
    <div class="panel-body text-center edgeworld-bg" style="height: 140px;">
      <a href="<?php echo url_for("@manifest-item?type={$type}") ?>">
        <img alt="<?php echo $type ?>" style="height: 120px;" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($type)) ?>">
      </a>
    </div>
  </div>
</div>
