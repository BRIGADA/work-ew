<?php use_helper('edgeworld') ?>
<div class="page-header clearfix">
  <img class="pull-right" alt="<?php echo $item->type ?>" style="max-height: 120px" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s', $item->image) ?>">
  <h1><?php echo __EW('items', $item->type, 'name') ?></h1>
  <p class="lead">
    <?php echo __EW('items', $item->type, 'description', false) ?>
  </p>
  <ul class="list-inline lead">
    <?php foreach ($item->tags as $tag): ?>
      <li><a href="<?php echo url_for("@manifest-items?filter={$tag}") ?>" class="label label-default"><?php echo $tag ?></a></li>
    <?php endforeach ?>
  </ul>
</div>

<div class="row">
  <?php if (!is_null($item->boost_type) || !is_null($item->boost_percentage) || !is_null($item->boost_amount)) : ?>
    <div class="col-md-4">
      <h3>Усиление</h3>
      <table class="table">
        <tr>
          <th>Вид</th>
          <td><?php echo $item->boost_type ?></td>
        </tr>
        <tr>
          <th>Количество</th>
          <td><?php echo $item->boost_amount ?></td>
        </tr>
        <tr>
          <th>Процент</th>
          <td><?php echo $item->boost_percentage ?></td>
        </tr>
      </table>
    </div>
  <?php endif ?>

  <?php if (!is_null($item->required_for_use)) : ?>
    <div class="col-md-4">
      <h3>Требуется для использования</h3>
      <ul>
        <?php foreach ($item->required_for_use as $type => $amount) : ?>
          <li><?php echo $amount ?>x <a href="<?php echo url_for("@manifest-item?type={$type}") ?>"><?php echo __EW('items', $type, 'name') ?></a></li>
        <?php endforeach ?>
      </ul>
    </div>  
  <?php endif ?>

  <?php if (!is_null($item->resource_type) || !is_null($item->resource_amount)) : ?>
    <div class="col-md-4">
      <h3>Ресурсы</h3>
      <table class="table">
        <tr>
          <th>Вид</th>
          <td><?php echo $item->resource_type ?></td>
        </tr>
        <tr>
          <th>Количество</th>
          <td><?php echo $item->resource_amount ?></td>
        </tr>
      </table>
    </div>
  <?php endif ?>

  <?php if ($item->contents) : ?>
    <div class="col-md-4">
      <h3>Содержимое</h3>
      <ul>
        <?php foreach ($item->contents as $content): ?>
          <li>
            <?php echo $content['quantity'] ?>x 
            <?php if (isset($content['item_id'])) : ?>
              <?php include_partial('itemLink', array('type' => ItemTable::getInstance()->findOneById($content['item_id'])->type)) ?>
            <?php elseif (isset($content['unit_type'])) : ?>
              <a href="<?php echo url_for("@manifest-unit?type={$content['unit_type']}") ?>"><?php echo __EW('units', $content['unit_type'], 'name') ?></a>
            <?php else : ?>
              неизвестная конструкция
            <?php endif ?>
          </li>
        <?php endforeach ?>
      </ul>
    </div>
  <?php endif ?>

</div>

<script type="text/javascript">
  $('.page-header img').click(function(){
    if($(this).data('full')) {
      $(this).data('full', false).css('max-height', '120px');
    }
    else {
      $(this).data('full', true).css('max-height', 'none');
    }
  });
</script>
