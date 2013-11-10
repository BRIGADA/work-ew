<?php use_helper('I18N')?>
<?php use_helper('highcharts')?>
<div class="page-header">
	<h1>Сравнение характеристик генералов</h1>
</div>

<div class="row">
	<?php foreach($stats as $stat => $series) : ?>
	<div class="span12">
	<?php echo Highcharts($stat, $series)?>
	</div>
	<?php endforeach ?>
	<?php if(count($generals) && count($skills)) : ?>
	<div class="span12">
		<h3>Скилы</h3>
		<table class="table table-striped table-bordered table-condensed">
			<tr>
				<th></th>
				<?php foreach ($generals as $general) : ?><th style="width: 100px; text-align: center;"><a href="<?php echo url_for("@manifest-general?type={$general->type}")?>"><?php echo $general->type ?></a></th><?php endforeach ?>
			</tr>
			<?php foreach($skills as $skill) : ?>
			<tr>
				<th style="text-align: right"><a href="<?php echo url_for("@manifest-skill?type={$skill->type}") ?>"><?php echo __(sprintf('skills.%s.name', strtolower($skill->type)), array(), 'ew-hero') ?></a></th>
				<?php foreach ($generals as $general) : ?><td style="text-align: center;"><?php if(in_array($skill->type, $general->skills->getRawValue())) : ?><i class="glyphicon glyphicon-ok"></i><?php endif ?></td><?php endforeach ?>
			</tr>
			<?php endforeach ?>
		</table>
	</div>
	<?php endif ?>
</div>