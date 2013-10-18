<?php use_helper('I18N')?>
<div class="page-header">
	<h1>Компании</h1>
	<button class="btn btn-primary btn-large" id="campaigns-update">Обновить</button>
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

<div class="modal hide fade" id="update-dialog">
    <div class="modal-header">
        <h3>Обновление...</h3>
    </div>
    <div class="modal-body">
        <p></p>
        <div class="progress">
            <div class="bar"></div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('#campaigns-update').click(function(){
	$('#update-dialog p').text('Загрузка актуальных данных');
	$('#update-dialog .bar').css('width', '0%');
	$('#update-dialog').modal('show');
	$.ajax({
		url: '<?php echo url_for('common/REMOTE') ?>',
		data: {
			path: '/api/manifest/campaigns.amf',
			decode: 'amf',
			element: 'campaigns'
		},
		success: function(response) {
			function process(index) {
				if(index >= response.length) {
					$('#update-dialog').modal('hide');
					return;
				}

				$('#update-dialog p').text(response[index].name);

				var data = {
					id: response[index].id,
					name: response[index].name,
					unlock_level: response[index].unlock_level,
					stages: window.JSON.stringify(response[index].stages )
				};

				$.post('<?php echo url_for('manifest/campaignUpdate') ?>', data, function(){
					process(index + 1);
				});

				$('#update-dialog .bar').css('width', ((index + 1) * 100 / response.length) + '%');
				
			}

			process(0);
		},
		error: function(){
			alert('request error');
		}
	});
	return false;
});
</script>