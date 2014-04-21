<?php use_helper('I18N') ?>

<button id="btn">try</button>

<script type="text/javascript">
$('#btn').click(function(){
    $.ajax({
	url: "<?php echo url_for('common/REMOTE')?>",
	data: {
	    path: '/api/player/store_purchase',
	    proxy: 'true',
	    query: {
		store_item: {
		    id: 219
		}
	    }
	    
	},
	type: 'POST',
	success: function(response) {
	    alert(response);
	}
    });
});
</script>