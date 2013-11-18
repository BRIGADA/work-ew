<?php use_helper('edgeworld') ?>
<?php use_helper('highcharts') ?>
<div class="page-header">
  <h1>Генералы</h1>
</div>
<div class="row">
  <?php foreach ($generals as $general) : ?>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title oneline-title">
            <a href="<?php echo url_for("@manifest-general?type={$general->type}") ?>"><?php echo __EW('units', $general->type, 'name') ?></a>
          </h3>
        </div>
        <div class="panel-body text-center edgeworld-bg">
          <a href="<?php echo url_for("@manifest-general?type={$general->type}") ?>">
            <img alt="ФОТКА" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/icons/square/units/%s.png', strtolower($general->type)) ?>" style="height: 300px;"/>
          </a>
        </div>
      </div>
    </div>
  <?php endforeach ?>
</div>

<p>
  <a href="<?php echo url_for('@manifest-generals-compare')?>" class="btn btn-default">Сравнение</a>
</p>