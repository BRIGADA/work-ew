<?php foreach ($messages as $row) : ?>
<?php $r = explode('::', $row['room'])[0]; ?>
<div class="panel <?php if ($r == 'global') : ?>panel-warning<?php elseif ($r == 'alliance') : ?>panel-info<?php else : ?>panel-default<?php endif ?>" data-id="<?php echo $row['id'] ?>">
  <div class="panel-heading"><strong><?php echo $row['user_card']['name'] ?></strong> <span class="text-muted">@ <?php echo $row['created_at'] ?></span></div>
  <div class="panel-body"><?php echo $row['message'] ?></div>
</div>
<?php endforeach; ?>