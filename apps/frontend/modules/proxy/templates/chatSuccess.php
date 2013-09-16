<div class="page_header">
	<h1>Чат</h1>
	<form>
		<select name="room">
			<option value="">&mdash;</option>
			<?php foreach($rooms as $r ) : ?>
			<?php if($r == $room) : ?>
			<option selected="selected"><?php echo $r ?></option>
			<?php else : ?>
			<option><?php echo $r ?></option>
			<?php endif ?>
			<?php endforeach ?>
		</select>
	</form>
</div>
<div>
	<table class="table">
		<?php foreach ($messages as $row) : ?>
		<?php $r = explode('::', $row->room)[0]; ?>
		<?php if($r == 'global') : ?>
		<tr class="error">
		<?php elseif ($r == 'alliance') : ?>
		<tr class="success">
		<?php else : ?>
		<tr>
		<?php endif ?>
			<td><?php echo $row->room ?></td>
			<th><?php echo $row->user_card['name'] ?></th>
			<td><?php echo $row->message ?></td>
			<td><?php echo $row->created_at ?></td>
		</tr>
		<?php endforeach ?>		
	</table>
</div>
<?php var_dump($rooms)?>
