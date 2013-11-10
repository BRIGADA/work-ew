<div class="page_header">
  <h1>Proxy</h1>
</div>

<?php if ($pager->haveToPaginate()) : ?>
  <ul class="pagination">
    <?php if (!$pager->isFirstPage()) : ?>
      <li><a href="<?php echo url_for("proxy/index?filter={$filter}&page={$pager->getFirstPage()}") ?>"><i class="glyphicon glyphicon-step-backward"></i></a></li>
    <?php endif ?>
    <?php foreach ($pager->getLinks(10) as $page): ?>
      <?php if ($page == $pager->getPage()): ?>
        <li class="active"><a href="<?php echo url_for("proxy/index?filter={$filter}&page={$page}") ?>"><?php echo $page ?></a></li>
      <?php else: ?>
        <li><a href="<?php echo url_for("proxy/index?filter={$filter}&page={$page}") ?>"><?php echo $page ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
    <?php if (!$pager->isLastPage()) : ?>
      <li><a href="<?php echo url_for("proxy/index?filter={$filter}&page={$pager->getLastPage()}") ?>"><i class="glyphicon glyphicon-step-forward"></i></a></li>
    <?php endif ?>
  </ul>
<?php endif ?>

<form action="<?php echo url_for('proxy/index') ?>">
  <select name="filter" onchange="submit();">
    <option value="">&mdash;</option>
    <?php foreach ($types as $type) : ?>
      <?php if ($filter == $type) : ?>
        <option selected="selected"><?php echo $type ?></option>
      <?php else : ?>
        <option><?php echo $type ?></option>
      <?php endif ?>
    <?php endforeach ?>
  </select>
</form>
<br>
<table class="table">
  <tr>
    <?php if (!$filter) : ?>
      <th>Тип</th>
    <?php endif ?>
    <th>Данные</th>
    <th class="col-lg-2">Время</th>
  </tr>
  <?php foreach ($pager->getResults() as $row) : ?>
    <tr>
      <?php if (!$filter) : ?>
        <td><?php echo $row->type ?></td>
      <?php endif ?>
      <td><?php var_dump($row->params->getRawValue()) ?></td>
      <td><?php echo $row->created_at ?></td>
    </tr>
  <?php endforeach ?>
</table>
