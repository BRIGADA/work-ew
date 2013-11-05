<?php use_helper('I18N') ?>
<?php use_javascript('jquery.lazyload.min.js')?>
<?php use_javascript('jquery.scrollstop.js')?>

<div class="page-header">
  <h1>Магазин</h1>
</div>

<ul class="thumbnails" id="store">
  <?php foreach ($result as $row) : ?>
    <li class="span3" id="item-<?php echo $row->item->type ?>">
      <div class="thumbnail">
        <div style="text-align: center">
          <a><?php echo $row->item->type ?></a>
        </div>
        <img alt="<?php echo $row->item->type ?>" style="height: 120px;" src="<?php echo image_path('loading.gif') ?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($row->item->type)) ?>">
        <div class="clearfix text-center">
          <?php if ($row->purchasable) : ?>
            <button class="pull-left btn btn-mini" titlte="<?php echo $row->price ?>">BUY</button>
          <?php endif ?>
            <span></span>
          <?php if ($row->usable) : ?>
            <button class="pull-right btn btn-primary action-use btn-mini">USE</button>
          <?php endif ?>
        </div>
      </div>
    </li>
  <?php endforeach ?>  
</ul>

<script type="text/javascript">
$(function(){
	$('#store img').lazyload({
		event: "scrollstop"
	});
    
    var items;
    
    $.ajax({
      url: '<?php echo url_for('common/REMOTE')?>',
      data: {
        path: '/api/player',
        element: 'response/items',
        proxy: true
      },
      success: function(response){
        items = response;
        
        $(response).each(function(){
          $('#item-'+this.type).toggle(this.quantity != 0).data('quantity', this.quantity).data('id', this.id).find('span').text(this.quantity);
        });
      }
    });
    
    $('.action-use').click(function(){
      $.ajax({
        url: '<?php echo url_for('common/REMOTE')?>',
        data: {
          path: '/api/player/items/'+$(this).closest('li').data('id'),
          query: {
            _method: 'delete',
            _basis_id: <?php echo $sf_user->getBaseID() ?>
          }
        },
        type: 'post',
        success: function(result){
          console.log(result);          
        }
      });
      return false;
    });

});
</script>