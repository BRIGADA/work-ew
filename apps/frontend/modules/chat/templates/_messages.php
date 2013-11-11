<?php foreach ($messages as $row) : ?>
    <?php $r = explode('::', $row['room'])[0]; ?>
    <div class="panel <?php if ($r == 'global') : ?>panel-warning<?php elseif ($r == 'alliance') : ?>panel-info<?php else : ?>panel-default<?php endif ?>" data-id="<?php echo $row['id'] ?>">
        <div class="panel-heading"><strong title="L<?php echo $row['user_card']['level'] ?>"><?php echo $row['user_card']['name'] ?></strong><?php if ($r != 'alliance' && isset($row['user_card']['alliance'])) : ?> &lt;<?php echo $row['user_card']['alliance']['name'] ?>&gt;<?php endif ?> <span class="text-muted pull-right"><?php echo $row['created_at'] ?></span></div>
        <div class="panel-body"><?php echo $row['message'] ?></div>
    </div>
<?php endforeach; ?>