<div class="page-header">
	<h1>Исследования</h1>
</div>

<div class="row">
	<div class="span12">
		<ul>
			<?php foreach($researches as $research) : ?>
			<li><a href="<?php echo url_for("@manifest-research?type={$research->type}")?>"><?php echo $research->type ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
</div>