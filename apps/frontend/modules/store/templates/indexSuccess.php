<?php use_helper('I18N') ?>
<?php use_helper('edgeworld') ?>
<?php use_javascript('jquery.lazyload.min.js') ?>
<?php use_javascript('jquery.scrollstop.js') ?>

<div class="page-header">
    <h1>Store</h1>
</div>
<p>
    <select id="base" class="form-control">
        <?php foreach ($bases as $base) : ?>
            <option value="<?php echo $base['id'] ?>"><?php echo $base['name'] ?></option>
        <?php endforeach ?>
    </select>
</p>

<div class="row" id="store-list">
    <?php foreach ($items as $item) : ?>
        <div class="col-xs-6 col-sm-4 col-md-3 col-lg-2">
            <div class="panel panel-default" data-type="<?php echo $item['type'] ?>" data-id="<?php echo $item['id'] ?>">
                <div class="panel-heading oneline-title">
                    <h4 class="panel-title"><?php echo __EW('items', $item['type'], 'name') ?></h4>
                </div>
                <div class="panel-body text-center edgeworld-bg clearfix">
                    <img alt="<?php echo $item['type'] ?>" style="height: 80px;" src="<?php echo image_path('loading.gif') ?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s.png', strtolower($item['type'])) ?>">
                </div>
                <div class="panel-footer clearfix">
                    <span class="quantity"><?php echo $item['quantity'] ?></span>
                    <span class="pull-right">
                        <button class="btn btn-xs btn-default use-one"><i class="glyphicon glyphicon-send"></i></button>
                        <button class="btn btn-xs btn-default use-all"><i class="glyphicon glyphicon-asterisk"></i></button>
                    </span>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>
<script type="text/javascript">
    $(function() {
//        $('.panel').each(function() {
//            var q = $(this).find('.quantity').text();
//            $(this).parent().toggle(q != 0);
//        });
        $('#store-list img').lazyload({
            event: "scrollstop"
        });

        $('.use-all').click(function() {
            var panel = $(this).closest('.panel').removeClass().addClass('panel panel-success');

            function use_all(panel) {
                use_item(panel, function() {
                    use_all(panel);
                });
            }

            use_all(panel);
        });

        $('.use-one').click(function() {

            use_item($(this).closest('.panel'), function() {
            });
        });

        function use_item(panel, callback) {
            var quantity = $(panel).find('.quantity').text();
            if (quantity > 0) {
                quantity--;
                $(panel).find('.quantity').text(quantity);

                $.ajax({
                    url: '<?php echo url_for('common/REMOTE') ?>',
                    type: 'post',
                    data: {
                        path: '/api/player/items/' + $(panel).data('id'),
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
                            $(panel).removeClass().addClass('panel panel-danger');
                        }
                    },
                    error: function() {
                        $(panel).removeClass().addClass('panel panel-danger');
                    }
                });
            }
            else {
                $(panel).removeClass().addClass('panel panel-warning');
            }
        }
    });
</script>
<?php //var_dump($manifest_store->getRawValue()) ?>
