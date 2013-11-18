<?php use_helper('edgeworld') ?>

<div class="page-header">
    <h1>Постройки</h1>
</div>

<div class="row">
    <?php foreach ($buildings as $building) : ?>
        <div class="col-sm-6 col-md-4 col-lg-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a href="<?php echo url_for("@manifest-building?type={$building->type}") ?>"><?php echo __EW('buildings', $building->type, 'name') ?></a>
                    </h3>
                </div>
                <div class="panel-body text-center edgeworld-bg">
                    <a href="<?php echo url_for("@manifest-building?type={$building->type}") ?>">
                        <img style="max-height: 150px" src="<?php echo image_path('http://kabam1-a.akamaihd.net/edgeworld/images/buildings/' . strtolower($building->type) . '_3.png') ?>"/>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>