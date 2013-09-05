<?php use_helper('I18N')?>
<div class="page-header clearfix">
	<img class="pull-right" alt="<?php echo $item->type?>" style="max-height: 100%; max-width: 200px; width: 100%" src="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($item->type))?>">
	<h1>
		<?php echo __(strtolower($item->type).'.name', array(), 'ew-items') ?>
	</h1>
	<p class="lead"><?php echo __(strtolower($item->type).'.description', array(), 'ew-items')?></p>
</div>

<div class="row">
	<?php if(!is_null($item->boost_type) || !is_null($item->boost_percentage) || !is_null($item->boost_amount)) : ?>
	<div class="span4">
		<h3>Усиление</h3>
		<table class="table">
			<tr>
				<th>Вид</th>
				<td><?php echo $item->boost_type ?></td>
			</tr>
			<tr>
				<th>Количество</th>
				<td><?php echo $item->boost_amount ?></td>
			</tr>
			<tr>
				<th>Процент</th>
				<td><?php echo $item->boost_percentage ?></td>
			</tr>
		</table>
	</div>
	<?php endif ?>
	
	<?php if(!is_null($item->resource_type) || !is_null($item->resource_amount)) : ?>
	<div class="span4">
		<h3>Ресурсы</h3>
		<table class="table">
			<tr>
				<th>Вид</th>
				<td><?php echo $item->resource_type ?></td>
			</tr>
			<tr>
				<th>Количество</th>
				<td><?php echo $item->resource_amount ?></td>
			</tr>
		</table>
	</div>
	<?php endif?>
	
	<?php if($item->contents) : ?>
	<div class="span4">
		<h3>Содержимое</h3>
		<ul>
			<?php foreach ($item->contents as $content):?>
			<li><?php echo $content['quantity']?>x <?php if(isset($content['item_id'])) : ?><a href="<?php echo url_for("@manifest-item-by-id?id={$content['item_id']}")?>">Предмет #<?php echo $content['item_id'] ?></a><?php elseif (isset($content['unit_type'])) : ?>Юнит <?php echo $content['unit_type'] ?><?php else : ?>неизвестная конструкция<?php endif ?></li>
			<?php endforeach ?>
		</ul>
	</div>
	<?php endif ?>
	
	<div class="span4">
		<h3>Тэги</h3>
		<ul>
			<?php foreach ($item->tags as $tag):?>
			<li><a href="<?php echo url_for("@manifest-items?filter={$tag}")?>"><?php echo $tag ?></a></li>
			<?php endforeach ?>
		</ul>
	</div>

</div>

<hr />

<a href="<?php echo url_for('@manifest-items') ?>">Список элементов</a>
