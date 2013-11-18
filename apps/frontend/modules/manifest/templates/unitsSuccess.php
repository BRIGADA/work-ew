<?php use_helper('edgeworld') ?>
<?php use_javascript('jquery.lazyload.min.js') ?>
<?php use_javascript('jquery.scrollstop.js') ?>

<div class="page-header">
    <h1>Юниты</h1>
    <button class="btn btn-primary btn-large" id="units-update">Обновить</button>
    <a href="<?php echo url_for('@manifest-units-compare') ?>" class="btn btn-large">Статистика</a>
</div>

<div class="row" id="units-list">
    <?php foreach ($units as $unit): ?>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <a href="<?php echo url_for('@manifest-unit?type=' . $unit->type) ?>"><?php echo __EW('units', $unit->type, 'name') ?></a>
                    </h3>
                </div>
                <div class="panel-body text-center edgeworld-bg">
                    <a href="<?php echo url_for('@manifest-unit?type=' . $unit->type) ?>">
                        <img alt="<?php echo $unit->type ?>" style="max-height:150px; min-height: 150px;" src="<?php echo image_path('loading.gif') ?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/units/%s.png', strtolower($unit->type)) ?>">
                    </a>
                </div>

            </div>
            <br>
        </div>
    <?php endforeach; ?>
</div>

<div class="modal hide fade" id="update-dialog">
    <div class="modal-header">
        <h3 id="update-header">Обновление</h3>
    </div>
    <div class="modal-body">
        <p id="update-description">Что именно обновляется…</p>
        <div class="progress progress-striped active">
            <div class="bar" id="update-progress" style="width: 0%;"></div>
        </div>
    </div>
    <!-- 
            <div class="modal-footer">
                    <a href="#" class="btn">Close</a> <a href="#" class="btn btn-primary">Save changes</a>
            </div>
    -->
</div>

<script type="text/javascript">
    $(function() {
        $('#units-list img').lazyload({
            event: "scrollstop"
        });

    });

    $('#units-update').click(function() {
        $('#update-header').text("Обновление");
        $('#update-description').text("Загрузка актуальных данных...");
        $('#update-dialog').modal();

        $.ajax({
            url: '<?php echo url_for('common/REMOTE') ?>',
            data: {
                path: '/api/manifest.amf',
                decode: 'amf',
                element: 'units',
                proxy: true
            },
            success: function(answer) {
                function updateElement(index) {
                    if (index >= answer.length) {
                        $('#update-dialog').modal('hide');
                        window.location.reload();
                        return;
                    }

                    $('#update-progress').css('width', (100 * (index + 1) / answer.length) + '%');
                    $('#update-description').text(answer[index].type);
                    $.ajax({
                        url: '<?php echo url_for('@manifest-unit-update') ?>',
                        type: 'post',
                        data: {
                            value: JSON.stringify(answer[index])
                        },
                        success: function(result) {
                            console.log('Success updated ' + answer[index].type);
                            updateElement(index + 1);
                        },
                        error: function() {
                            console.log('Error updating ' + answer[index].type);
                            $('#update-dialog').modal('hide');
                        }
                    });
                }
                updateElement(0);
            },
            error: function() {
                console.log("Failed to get actual data");
                $('#update-dialog').modal('hide');
            }

        });

        return false;
    });
</script>