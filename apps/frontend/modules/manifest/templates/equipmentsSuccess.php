<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Снаряжение</h1>
	<p class="lead">Всего: <?php echo $equipments->count()?></p>

	<button id="equipment-update" class="btn btn-large btn-primary">Обновить</button>
</div>

<div class="row">
	<div class="span12">
		<ul>
            <?php foreach($equipments as $equipment) : ?>
            <li title="<?php echo $equipment->type ?>">
                <a href="<?php echo url_for("@manifest-equipment?type={$equipment->type}")?>"><?php echo __(strtolower($equipment->type).'.name', array(), 'ew-items') ?></a>
			</li>
            <?php endforeach ?>
        </ul>
	</div>
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
</div>

<script type="text/javascript">
$('#equipment-update').click(function(){
	var canceled = false;
	$('#update-dialog').modal();
	$('#update-description').text('Загрузка актуальных данных...');
	$.ajax({
		url: '<?php echo url_for('common/RGET')?>',
		data: {
			path: '/api/manifest/equipment',
			element: 'response/equipment'
		},
		success: function(answer) {
			console.log('success');

			function up(index) {				
				$('#update-progress').css('width', (index * 100 / answer.length) + '%');
				
				if(index >= answer.length) {
					window.location.reload();
					return;
				}

				$('#update-description').text(answer[index].type);

				$.ajax({
					url: '<?php echo url_for('@manifest-equipment-update')?>',
					data: answer[index],
					type: 'POST',
					success: function() {
						up(index + 1);
					},
					error: function(){
						alert('Update error');
						$('#update-dialog').modal('hide');
					}
				});				
			}
			up(0);
		},
		error: function() {
			console.log('failed');
		}
	});
	return false;
});
</script>
