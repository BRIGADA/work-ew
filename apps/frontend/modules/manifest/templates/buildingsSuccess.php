<?php use_helper('edgeworld')?>

<div class="page-header">
    <h1>Постройки</h1>
</div>

<ul class="thumbnails">
    <?php foreach ($buildings as $building) : ?>
    <li class="span3">
        <div class="thumbnail">
            <img style="max-height: 150px" src="<?php echo image_path('http://kabam1-a.akamaihd.net/edgeworld/images/buildings/'.  strtolower($building->type) .'_3.png') ?>"/>
            <a href="<?php echo url_for("@manifest-building?type={$building->type}") ?>"><?php echo __EW('buildings', $building->type, 'name') ?></a>
        </div>
    </li>
    <?php endforeach ?>
</ul>
