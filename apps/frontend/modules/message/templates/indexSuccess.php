<table class="table table-stripped table-bordered">
	<tr>
		<th>created_at</th>
		<th>type</th>
		<th></th>
	</tr>
	<tbody>
    <?php foreach ($result->messages as $i => $message) : ?>    
    <tr class="<?php if(!$message->read):?>info<?php endif ?>" title="<?php echo $message->type ?>">
        <td><?php echo date('Y-m-d h:i:s', $message->created_at) ?></td>
        <td><?php include_partial("title-{$message->type}", array('message'=>$message)) ?>
        <!-- <br><span class="muted"><?php echo $message->type ?></span> -->
        </td>
        <td>
            <a href="#message-<?php echo $message->id?>" class="btn btn-mini" data-toggle="modal"><i class="glyphicon glyphicon-envelope"></i></a>
            <div id="message-<?php echo $message->id?>" class="modal hide fade" tabindex="-1" data-remote="<?php echo url_for("message/read?id={$message->id}")?>">
            		<div class="modal-header">
            				<h3><?php include_partial("title-{$message->type}", array('message'=>$message)) ?></h3>
            		</div>
                <div class="modal-body">
                    <p>One fine body…</p>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal">Удалить</button>
                    <button class="btn btn-primary" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </td>
	</tr>
	<?php endforeach ?>
	</tbody>
</table>


<?php var_dump($result->getRawValue()->messages[0])?>