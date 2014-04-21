<?php use_helper('edgeworld') ?>
<div class="page-header">
    <h1>FORCE UNIT BOX</h1>
</div>
<select id="base" class="form-control">
    <?php foreach ($bases as $base) : ?>
        <option value="<?php echo $base['id'] ?>"><?php echo $base['name'] ?></option>
    <?php endforeach ?>
</select>
<br>

<table class="table table-condensed" id="units">
    <thead>
        <tr>
            <th>Type</th>
            <th style="width: 100px">Quantity</th>
            <th style="width: 100px">Force</th>
            <th style="width: 100px">Total</th>
            <th style="width: 70px"></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
            <?php if ($item['quantity'] && $force[$item['type']]) : ?>
                <tr data-id="<?php echo $item['id'] ?>" data-quantity="<?php echo $item['quantity'] ?>">
                    <th><?php echo __EW('items', $item['type'], 'name') ?></th>
                    <td><?php echo $item['quantity'] ?></td>
                    <td><?php echo $force[$item['type']] ?></td>
                    <td><?php echo $force[$item['type']] * $item['quantity'] ?></td>
                    <td>
                        <button class="btn btn-default btn-xs action-use"><i class="glyphicon glyphicon-check"></i></button>
                        <button class="btn btn-default btn-xs action-use-all"><i class="glyphicon glyphicon-asterisk"></i></button>
                    </td>
                </tr>
            <?php endif ?>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td id="total-force" class="lead" colspan="2"></td>
        </tr>
    </tfoot>
</table>

<div class="well text-right" style="font-size: 2em; display: none;" id="autojob-stat">
    Rate: <strong></strong> box/sec
</div>
<script type="text/javascript">

    function updateTotal() {
        var total = 0;
        $('#units > tbody > tr').each(function() {
            total += parseFloat($(this).children('td:eq(2)').text());
        });

        $('#total-force').text(total);
    }

    updateTotal();
    
    var autojob_time = null;
    var autojob_count = 0;

    $('.action-use-all').click(function() {
        $(this).closest('tr').find('button').hide();
        function use_all(tr) {
            if(autojob_time == null) {
                $('#autojob-stat').show();
                autojob_count = 0;
                autojob_time = new Date();
            }
            
            use_item(tr, function() {
                autojob_count++;
                var curtime = new Date();
                var rate = autojob_count * 1000 / (curtime - autojob_time);
                $('#autojob-stat strong').text(rate.toFixed(3));
                use_all(tr);
            });            
        }
        use_all($(this).closest('tr'));
        $(this).closest('tr').addClass('success');
    });

    $('.action-use').click(function() {
        use_item($(this).closest('tr'), function() {
        });
    });

    function use_item(tr, callback) {
        var quantity = $(tr).data('quantity');
        if (quantity > 0) {
            quantity--;
            $(tr).data('quantity', quantity);
            $(tr).children('td:eq(0)').text(quantity);
            $(tr).children('td:eq(2)').text(parseFloat($(tr).children('td:eq(1)').text()) * quantity);
            updateTotal();

            $.ajax({
                url: '<?php echo url_for('common/REMOTE') ?>',
                type: 'post',
                data: {
                    path: '/api/player/items/' + $(tr).data('id'),
                    proxy: true,
                    element: 'response',
                    query: {
                        basis_id: $('#base').val(),
                        _method: 'delete'
                    }
                },
                success: function(response) {
                    if (response.success) {
                        callback();
                    }
                    else {
                        $(tr).removeClass().addClass('danger');
                    }
                },
                error: function() {
                    $(tr).removeClass().addClass('danger');
                }
            });
        }
        $(tr).find('td:eq(3) button').attr('disabled', quantity <= 0);
    }

    $('#units > tbody').sortable();
</script>
