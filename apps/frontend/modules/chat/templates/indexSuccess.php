<div class="page-header">
    <h1>Чат</h1>
</div>

<!--
<div class="input-group" id="sender">
  <div class="input-group-btn" id="channel" data-value="local">
    <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span>Local</span> <span class="caret"></span></button>
    <ul class="dropdown-menu">
      <li><a href="#" data-value="local">Local</a></li>
      <li><a href="#" data-value="alliance">Alliance</a></li>
      <li><a href="#" data-value="global">Global</a></li>
    </ul>
  </div>
  <input type="text" class="form-control" id="new-message" placeholder="введите сообщение..." autofocus/>
</div>
<hr>
-->

<div id="messages">
    <?php include_partial('messages', ['messages' => $messages]) ?>
</div>

<audio preload="auto" id="sound-incoming">
    <source src="<?php echo public_path('/sounds/incoming-message.ogg') ?>" type="audio/ogg" />
    <source src="<?php echo public_path('/sounds/incoming-message.mp3') ?>" type="audio/mp3" />
</audio>

<?php if (count($messages)) : ?>
    <button class="btn btn-danger btn-block" id="fetch-previous">Ещё...</button>
    <script type="text/javascript">
        $('#fetch-previous').click(function() {
            $.ajax({
                url: '<?php echo url_for('chat/old') ?>',
                data: {
                    id: $('#messages > :last').data('id')
                },
                success: function(response) {
                    if (response === '') {
                        $('#fetch-previous').hide();
                        return;
                    }
                    $(response).appendTo('#messages');
                }
            });
        });
    </script>
<?php endif ?>

<script type="text/javascript">
    $('#new-message').keypress(function(event) {
        var v = $.trim($(this).val());
        if (event.which === 13 && v.length) {
            console.log({channel: $('#channel').data('value'), message: $('#new-message').val()});
            $(this).val('');
        }
    });

    $(document).ready(function() {
        setInterval(function() {
            $.ajax({
                url: '<?php echo url_for('chat/new') ?>',
                data: {
                    id: $('#messages > :first').data('id')
                },
                success: function(response) {
                    if (response !== '') {
                        $('#sound-incoming').get(0).play();
                        $(response).hide().prependTo('#messages').slideDown();
                    }
                }
            });
        }, 5000);
    });

    $('#channel ul a').click(function() {
        console.log($(this).data('value'));
        $('#channel').data('value', $(this).data('value'));
        $('#channel > button > span:first').text($(this).text());
    });

</script>