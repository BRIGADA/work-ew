<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Снаряжение</h1>
	<p class="lead">Всего: <?php echo $equipments->count()?></p>
</div>

<ul>
    <?php foreach($equipments as $equipment) : ?>
    <li title="<?php echo $equipment->type ?>">
        <a href="<?php echo url_for("@manifest-equipment?type={$equipment->type}")?>"><?php echo __(strtolower($equipment->type).'.name', array(), 'ew-items') ?></a>
    </li>
    <?php endforeach ?>
</ul>