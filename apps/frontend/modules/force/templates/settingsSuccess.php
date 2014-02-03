<?php use_helper('edgeworld') ?>
<form action="<?php echo url_for('force/settings') ?>" method="post">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Тип</th>
                <th style="width: 150px;">Значение</th>
                <th style="width: 150px;"><input id="multiplier" class="form-control" value="1"/></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($units as $unit) : ?>
                <tr>
                    <th><a href="<?php echo url_for("@manifest-item?type={$unit['type']}") ?>"><?php echo __EW('items', $unit['type'], 'name') ?></a></th>
                    <td>
                        <input class="form-control" type="text" name="data[<?php echo $unit['type'] ?>]" value="<?php if (isset($force[$unit['type']])) : ?><?php echo $force[$unit['type']] ?><?php endif ?>"/>
                    </td>
                    <td>
                        <input class="form-control" type="text" value="<?php if (isset($force[$unit['type']])) : ?><?php echo $force[$unit['type']] ?><?php endif ?>"/>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
    <button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-ok"></i> Сохранить</button>
    <a href="<?php echo url_for('force/settingsExport') ?>" class="btn btn-warning"><i class="glyphicon glyphicon-export"></i> Экспорт</a>
    <a href="<?php echo url_for('force/settingsImport') ?>" class="btn btn-danger"><i class="glyphicon glyphicon-import"></i> Импорт</a>
</form>

