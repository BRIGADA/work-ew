<?php use_helper('edgeworld') ?>
<div class="page-header clearfix">
  <img src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($token['type'])) ?>" class="pull-right">
  <h1><?php echo __EW('items', $token['type'], 'name') ?></h1>
  <p class="lead"><?php echo __EW('items', $token['type'], 'description') ?></p>
</div>

<blockquote>
  <p><?php echo __EW('items', $token['type'], 'tooltip') ?></p>
</blockquote>

<?php var_dump($token->getRawValue()->stat_names) ?>
<?php foreach($token->getRawValue()->stat_names as $stat) : ?>
<h3><?php echo $stat ?></h3>
  <?php var_dump($token->getStatSerie($stat)->getRawValue()) ?>
<?php endforeach ?>

