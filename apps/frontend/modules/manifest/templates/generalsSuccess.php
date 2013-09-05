<?php use_helper('I18N')?>
<?php use_helper('highcharts')?>
<div class="page-header">
	<h1>Генералы</h1>
	<p>
		<a href="#" class="btn btn-primary">Обновить</a>
		<a href="<?php echo url_for('@manifest-generals-compare') ?>" class="btn">Сравнение</a>
	</p>
</div>
<div class="row">
	<?php foreach ($generals as $general) : ?>
	<div class="span3" style="margin-bottom: 20px;">
		<div class="thumbnail text-center" style="background: radial-gradient(ellipse at center, #a7cfdf 0%, #23538a 100%);" title="<?php echo __(sprintf('%s.fighterclass', strtolower($general->type)), array(), 'ew-units')?>">
			<a style="color: white;" href="<?php echo url_for("@manifest-general?type={$general->type}")?>"><img alt="ФОТКА" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/icons/square/units/%s.png', strtolower($general->type))?>" style="height: 300px;"/><br><?php echo __(sprintf('%s.name', strtolower($general->type)), array(), 'ew-units') ?></a>
		</div>
	</div>
	<?php endforeach ?>
</div>
