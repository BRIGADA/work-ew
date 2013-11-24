<div class="modal fade" id="msg-<?php echo $message->id ?>">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php include_partial($message->type, array('message' => $message)) ?>
      <div class="modal-footer">        
        <button type="button" data-dismiss="modal" class="btn btn-default">Закрыть</button>
      </div>        
    </div>
  </div>
</div>