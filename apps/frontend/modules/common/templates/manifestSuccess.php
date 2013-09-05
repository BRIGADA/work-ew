<div class="page-header">
<h1>Обновление справочников</h1>
</div>
<button class="btn btn-large btn-primary" id="manifest-update">Начать</button>
<button class="btn" id="ttt">ggg</button>


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
$('#ttt').click(function(){
	$.ajax({
		url: '<?php echo url_for('common/RGET')?>',
		data: {
			path: '/api/manifest/compaigns.amf',
//			decode: 'base64'
		},
		success: function(response){
			console.log(response);
		}
	});
});
$('#manifest-update').click(function(){
	$('#update-header').text("Снаряжение");
	$('#update-description').text("Загрузка актуальных данных...");
	$('#update-dialog').modal();
	
	$.ajax({
		url: "<?php echo url_for('common/RGET') ?>",
		data: {
			path: '/api/manifest/equipment'
		},
		success: function(answer){
			function updateEquipment(index) {
				if(index >= answer.response.equipment.length) {
					$('#update-dialog').modal('hide');
					return;
				}

				$('#update-progress').css('width', (100 * index / answer.response.equipment.length) + '%');
				$('#update-description').text(answer.response.equipment[index].type);
				$.ajax({
					url: '<?php echo url_for('common/updateEquipment')?>',
					type: 'post',
					data: {
						data: answer.response.equipment[index]
					},
					success: function(result){
//						console.log(result);
						updateEquipment(index + 1);
					},
					error: function(){
						console.log('error updating equipment');
					}
				});
			}
			updateEquipment(0);
//			console.log(answer);
		}
	});
	return false;
});
</script>
