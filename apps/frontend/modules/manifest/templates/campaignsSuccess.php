<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Компании</h1>
	<button class="btn btn-primary btn-large" id="items-update">Обновить</button>
</div>

<div class="row">
	<div class="span12">
		<ul>
			<?php foreach($campaigns as $campaign) :?>
			<li><a href="<?php echo url_for("@manifest-campaign?id={$campaign->id}")?>"><?php echo __(sprintf('campaign.%s.name', $campaign->name), array(), 'ew-ew')?></a></li>
			<?php endforeach ?>
		</ul>
	</div>
</div>