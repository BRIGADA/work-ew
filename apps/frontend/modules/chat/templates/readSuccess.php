<h1><?php echo $room ?></h1>

<div id="messages">
<?php include_partial('messages', array('result'=>$result))?>
</div>
<script type="text/javascript">
<!--
	$(function(){
		setInterval(function(){
			$.ajax({
				url: '<?php echo url_for('chat/new')?>',
				data: {
					room: '<?php echo $room ?>',
					id: $('#messages > div').first().data('id')
				},
				success: function(response){
					$(response).each(function(){
						$(this).prependTo('#messages');
					});
				}
			});
		}, 5000);
	});

//-->
</script>
<ul>
</ul>