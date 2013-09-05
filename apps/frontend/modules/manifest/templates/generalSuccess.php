<?php use_helper('I18N')?>
<?php use_helper('highcharts')?>

<div class="page-header clearfix">
	<img alt="ФОТКА" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/icons/square/units/%s.png', strtolower($general->type))?>" class="pull-right" style="height: 100px">
	<h1><?php echo __(sprintf('%s.name', strtolower($general->type)), array(), 'ew-units') ?></h1>
	<p class="lead"><?php echo __(sprintf('%s.fighterclass', strtolower($general->type)), array(), 'ew-units') ?></p>
</div>

<?php $categories = array(); foreach ($general->levels as $level) $categories[] = sprintf('L%d', $level->level);?>
<div class="row">
	<?php foreach ($general->stats as $stat):?>
	<div class="span6">
		<h3><?php echo $stat ?></h3>
		<?php echo Highcharts(null, array($stat=>$general->getStatData($stat)), array('xAxis'=>array('categories'=>$categories)))?>
	</div>
	<?php endforeach ?>
	<div class="span6">
		<h3>Скилы</h3>
		<ul>
			<?php foreach ($general->skills as $skill ) : ?>
			<li><a href="<?php echo url_for("@manifest-skill?type={$skill}")?>"><?php echo __(sprintf('skills.%s.name', strtolower($skill)), array(), 'ew-hero')?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
</div>
