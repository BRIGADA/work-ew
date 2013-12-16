<div class="page-header">
  <h1>Характеристики снаряжения</h1>
</div>
<ol>
  <?php foreach ($stats as $stat) : ?>
    <li><?php echo $stat ?></li>
  <?php endforeach ?>
</ol>
<table class="table table-condensed table-bordered table-striped" id="results">
  <thead>
    <tr>
      <th>&mdash;</th>
      <?php foreach ($stats as $num => $stat) : ?>
        <th title="<?php echo $stat ?>" style="width: 30px"><?php echo $num + 1 ?></th>
        <?php endforeach ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($equipments as $equipment) : ?>
      <tr>
        <td class="text-right"><a href="<?php echo url_for("@manifest-equipment?type={$equipment['type']}")?>"><?php echo $equipment['type'] ?></a></td>
        <?php foreach ($stats as $stat) : ?>
        <?php if(in_array($stat, $equipment['stats_types']->getRawValue())) : ?>
        <td style="background-color: #b2dba1;" class="text-center">&plus;</td>
        <?php else : ?>
        <td></td>
        <?php endif ?>
        <?php endforeach ?>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<script type="text/javascript">
//  $()
</script>