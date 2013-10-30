<div class="page-header">
    <h1>Постройки</h1>
</div>

<ul>
    <?php foreach ($buildings as $building) : ?>
    <li><a href="<?php echo url_for("@manifest-building?type={$building->type}") ?>"><?php echo $building->type ?></a></li>
    <?php endforeach ?>
</ul>
