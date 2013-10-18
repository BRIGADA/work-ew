<?php use_helper('I18N')?>
<?php use_javascript('jquery.lazyload.min.js')?>
<?php use_javascript('jquery.scrollstop.js')?>

<div class="page-header">
	<h1>Элементы</h1>
	<p class="lead">Всего: <?php echo $items->count()?></p>
	<button class="btn btn-primary btn-large" id="items-update">Обновить</button>
</div>
<div>
	<select id="filter">
		<option value="">&mdash;</option>
		<?php foreach($tags as $tag) : ?>
		<option value="<?php echo $tag?>"><?php echo $tag ?></option>
		<?php endforeach ?>
	</select>
</div>

<div class="row" id="items-list">
<?php foreach ($items as $item): ?>
	<div class="span3 <?php foreach($item->tags as $tag):?> tag-<?php echo $tag ?><?php endforeach ?>">
		<div class="thumbnail">
			<div class="img-polaroid text-center" style="height: 120px; background: linear-gradient(to bottom, #3f4c6b 0%, #5aaaad 100%);">
				<a href="<?php echo url_for('@manifest-item?type='.$item->type) ?>">
				<img alt="<?php echo $item->type?>" style="height: 120px;" src="<?php echo image_path('loading.gif')?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($item->type))?>">
				</a>
			</div>

			<div class="clearfix" style="padding-top: 5px">
				<a href="<?php echo url_for('@manifest-item?type='.$item->type) ?>"><?php echo __(strtolower($item->type).'.name', array(), 'ew-items') ?></a>
				<?php if($item->permanent) : ?><span class="pull-right badge badge-important">PERM</span><?php endif?>
			</div>
		</div>
		<br>
	</div>
<?php endforeach; ?>
</div>

<div class="modal hide fade" id="update-dialog">
	<div class="modal-header">
		<h3 id="update-header">Обновление</h3>
	</div>
	<div class="modal-body">
		<p id="update-description">Что именно обновляется…</p>
		<div class="progress progress-striped active">
			<div class="bar" id="update-progress" style="width: 0%;"></div>
		</div>
	</div>
<!-- 
	<div class="modal-footer">
		<a href="#" class="btn">Close</a> <a href="#" class="btn btn-primary">Save changes</a>
	</div>
 -->
</div>

<script type="text/javascript">
$(function(){
	$('#items-list img').lazyload({
		event: "scrollstop"
	});

});

$('#filter').change(function(){
	if($(this).val()) {
		$('#items-list > div.tag-'+$(this).val()).show();
		$('#items-list > div:not(.tag-'+$(this).val()+')').hide();
	}
	else {
		$('#items-list > div').show();
	}

	$(window).trigger('scrollstop');

});

<?php if(isset($filter)) : ?>
$('#filter').val('<?php echo $filter?>').change();
<?php endif ?>


$('#items-update').click(function(){
	$('#update-header').text("Обновление");
	$('#update-description').text("Загрузка актуальных данных...");
	$('#update-dialog').modal();

	$.ajax({
		url: '<?php echo url_for('common/REMOTE') ?>',
		data: {
			path: '/api/manifest.amf',
			decode: 'amf',
			element: 'items'
		},
		success: function(answer){
			function updateElement(index) {
				if(index >= answer.length) {
					$('#update-dialog').modal('hide');
					window.location.reload();
					return;					
				}

				$('#update-progress').css('width', (100 * index / answer.length) + '%');
				$('#update-description').text(answer[index].type);
				$.ajax({
					url: '<?php echo url_for('@manifest-item-update')?>',
					type: 'post',
					data: {
						data: answer[index]
					},
					success: function(result){
						console.log('Success updated ' + answer[index].type);
						updateElement(index + 1);
					},
					error: function(){
						console.log('Error updating ' + answer[index].type);
						$('#update-dialog').modal('hide');						
					}
				});				
			}			
			updateElement(0);
		},
		error: function(){
			console.log("Failed to get actual data");
			$('#update-dialog').modal('hide');
		}
		
	});
	
	return false;
});
</script>