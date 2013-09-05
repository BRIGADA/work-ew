<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Скилы</h1>
</div>
<div class="row">
	<div class="span12">
		<ul>
			<?php foreach ($skills as $skill) : ?>
			<li>
				<a href="<?php echo url_for("@manifest-skill?type={$skill->type}")?>"><?php echo __(sprintf('skills.%s.name', strtolower($skill->type)), array(), 'ew-hero') ?></a>
			</li>
			<?php endforeach ?>
		</ul>
	</div>
</div>