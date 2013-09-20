<h1>Строения</h1>

<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th>Type</th>
      <th style="width: 100px">Size</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($buildings as $building): ?>
    <tr>
      <td><a href="<?php echo url_for('building/show?id='.$building->getId()) ?>"><?php echo $building->getType() ?></a></td>
      <td><?php echo $building->size[0] ?>x<?php echo $building->size[1] ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>