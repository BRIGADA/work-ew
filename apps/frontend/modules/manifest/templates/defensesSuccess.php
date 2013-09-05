<div class="page-headers">
	<h1>Оборона</h1>
</div>

<div class="row">
	<div class="span12">
		<ul>
			<?php foreach ($defenses as $defense) :?>
			<li><a href="<?php echo url_for("@manifest-defense?type={$defense->type}") ?>"><?php echo $defense->type ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
</div>
