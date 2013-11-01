<?php use_javascript('bootbox.min.js') ?>
<button class="btn" id="lottery" disabled2="disabled">ZOOT!</button>
<ol id="log">
</ol>
<script type="text/javascript">
    var items = <?php echo json_encode($items->getRawValue()) ?>;
    
    $(function(){
        for(var index in items) {
            if(items[index].type === 'LotteryToken' && items[index].quantity) {                
                $('#lottery').removeAttr('disabled').text(items[index].quantity + "x ZOOT!");
                break;
            }            
        }
    });

    $('#lottery').click(function() {
        function lottery() {
            for (var index in items) {
                if (items[index].type === 'LotteryToken' /*&& items[index].quantity*/) {
                    $.ajax({
                        url: '<?php echo url_for('common/REMOTE') ?>',
                        type: 'post',
                        data: {
                            path: '/api/lottery',
                            proxy: true,
                            element: 'response'
                        },
                        success: function(result) {
                            if(result.success) {
                                $('#log').append('<li>'+result.prize+'</li>');
                                items = result.items;
                                lottery();
                            }
                            else {
                                if(result.hasOwnProperty('errors')) {
                                    bootbox.alert(result.errors.join('\n'));
                                }
                                else {
                                    console.log(result);
                                }
                            }
                        }

                    });
                    return;
                }
            }
            bootbox.alert('Завершено!');
        }
        lottery();
    });
</script>