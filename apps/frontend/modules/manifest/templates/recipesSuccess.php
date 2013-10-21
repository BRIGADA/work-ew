<div class="page-header">
    <h1>Рецепты</h1>
    <p><button id="update" class="btn btn-primary"><i class="icon-refresh icon-white"></i> Обновить</button></p>
</div>

<ul>
<?php foreach ($recipes as $recipe) : ?>
<li><a href="<?php echo url_for("@manifest-recipe?name={$recipe->name}")?>"><?php echo $recipe->name ?></a></li>
<?php endforeach ?>
</ul>

<script type="text/javascript">
$('#update').click(function(){
	$.get('<?php echo url_for('common/REMOTE')?>', { path: '/api/manifest.amf', decode: 'amf', element: 'crafting_recipes'}, function(recipes){
		function update(index) {
			if(index >= recipes.length) {
				console.log('update complete');
				return;
			}
			$.ajax({
				type: 'post',
				url: '<?php echo url_for('@manifest-recipe-update') ?>',
				data: recipes[index],
				success: function() {
					console.log('update success');
					update(index + 1);
				},
				error: function() {
					console.log('update failed');
				}
			});
		}
		update(0);
	});
	return false;
});
</script>