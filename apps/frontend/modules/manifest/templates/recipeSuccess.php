<?php use_helper('I18N') ?>
<div class="page-header">
    <h1>Рецепт &laquo;<?php echo __($recipe->name.'.name', null, 'ew-recipes') ?>&raquo;</h1>
    <p class="lead"><?php echo __($recipe->name.'.description', null, 'ew-recipes') ?></p>
</div>

<div class="row">
<div class="span6">
<h2>Вход</h2>
<ul>
<?php foreach ($recipe->inputs as $a) : ?>
<li>
<?php if($a['type'] == 'equipment') : ?>
<?php printf('%ux \'%s\' equipment', $a['quantity'], $a['tier']) ?>
<?php elseif ($a['type'] == 'item') : ?>
<?php echo $a['quantity'] ?>x <a href="<?php echo url_for("@manifest-item?type={$a['name']}")?>"><?php echo $a['name'] ?></a>
<?php else : ?>
<?php var_dump($recipe->inputs->getRawValue()) ?>
<?php endif ?>
</li>
<?php endforeach ?>
</ul>
</div>
<div class="span6">
<h2>Выход</h2>
<ul>
<?php foreach($recipe->outputs as $a) : ?>
<li>
<?php if($a['type'] == 'equipment') : ?>
<?php echo $a['quantity']?>x <a href="<?php echo url_for("@manifest-equipment?type={$a['name']}")?>"><?php echo $a['name'] ?></a>
<?php elseif($a['type'] == 'item') : ?>
<?php echo $a['quantity']?>x <a href="<?php echo url_for("@manifest-item?type={$a['name']}")?>"><?php echo $a['name'] ?></a>
<?php else : ?>
<?php var_dump($recipe->outputs->getRawValue())?>
<?php endif ?>
</li>
<?php endforeach ?>
</ul>
</div>
</div>