<div class="page-header">
  <h1>Почта</h1>  
</div>

<table class="table table-stripped table-bordered" id="messages">
  <tr>
    <th style="width: 150px;">created_at</th>
    <th>type</th>
  </tr>
  <tbody>
    <?php foreach ($result->messages as $i => $message) : ?>    
      <tr class="<?php if (!$message->read): ?>success<?php endif ?>" title="<?php echo $message->type ?>" data-id="<?php echo $message->id ?>">
        <td class="text-muted"><?php echo date('H:i:s d.m.Y', $message->created_at) ?></td>
        <td><?php include_partial("title-{$message->type}", array('message' => $message)) ?></td>
      </tr>
    <?php endforeach ?>
  </tbody>
</table>

<script>
$('#messages > tbody > tr').click(function(){
  if($('#msg-'+$(this).data('id')).size()) {
    $('#msg-'+$(this).data('id')).modal();
  }
  else {
    $.get('<?php echo url_for('@mail-read') ?>', {id: $(this).data('id')}, function(response){
      $(response).appendTo('body').modal().on('shown.bs.modal', function(){
        $(this).find('button:last').focus();
      });
    });
  }
});

</script>

<?php //var_dump($result->getRawValue()->messages[0]) ?>
