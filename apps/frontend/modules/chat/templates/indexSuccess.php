<h1>Chats :)</h1>
<ul>
<?php foreach ($results as $room) : ?>
<li><?php echo link_to($room, "chat-read/{$room}")?></li>
<?php endforeach ?>
</ul>
