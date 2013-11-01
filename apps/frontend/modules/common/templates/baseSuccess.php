<?php use_helper('I18N') ?>
<?php use_javascript('pixi.js') ?>

<h1><?php echo $result['response']['base']['name'] ?></h1>

<ul>
    <?php foreach ($sf_user->getAttribute('bases', array(), 'player') as $name => $id): ?>
        <li><a href="<?php echo url_for("common/base?id={$id}") ?>"><?php echo $name ?></a></li>
    <?php endforeach ?>
</ul>
<!--
<table class="table table-condensed table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Type</th>
            <th>X</th>
            <th>Y</th>
            <th>Level</th>
            <th>HP</th>
        </tr>
    </thead>
<?php //foreach ($result['response']['base']['buildings'] as $building) : ?>
        <tr data-id="<?php // echo $building['id']    ?>">
            <td><?php // echo $building['id']    ?></td>
            <td><?php // echo __(strtolower($building['type']) . '.name', null, 'ew-buildings')    ?></td>
            <td><?php // echo $building['x']    ?></td>
            <td><?php // echo $building['y']    ?></td>
            <td><?php // echo $building['level']    ?></td>
            <td><?php // echo $building['hp']    ?></td>
            <td><button class="btn btn-mini action-upgrade"><i class="icon-arrow-up"></i></button></td>
        </tr>
<?php // endforeach ?>
-->
</table>
<script type="text/javascript">
    $('.action-upgrade').click(function() {
        var base_id = <?php echo $result['response']['base']['id'] ?>;
        var building_id = $(this).closest('tr').data('id');

        $.ajax({
            url: '<?php echo url_for('common/REMOTE') ?>',
            data: {
                path: '/api/bases/' + base_id + '/buildings/' + building_id,
                query: {
                    _method: 'put'
                }
            },
            type: 'post',
            success: function(result) {
                console.log(result);
            }
        });
        return false;
    });
</script>

<canvas width="<?php echo $result['response']['base']['width'] ?>" height="<?php echo $result['response']['base']['length'] ?>" style="border: 1px solid green;" id="base"></canvas>
<script type="text/javascript">
    var buildings = <?php echo json_encode($result['response']['base']['buildings']->getRawValue(), JSON_NUMERIC_CHECK) ?>;
    var manifest = <?php echo json_encode($manifest->getRawValue(), JSON_NUMERIC_CHECK) ?>;
    var w = <?php echo $result['response']['base']['width'] ?>;
    var h = <?php echo $result['response']['base']['length'] ?>;
    var p = 0;

    var renderer = PIXI.autoDetectRenderer(w, h, document.getElementById('base'));
    var stage = new PIXI.Stage(0xffffff, true);
    stage.setInteractive(true);

    var grid = new PIXI.Graphics();
    grid.beginFill(0xff0000);
    for (var x = 0; x < w / 10; x++) {
        grid.lineStyle(x % 10 ? 1 : 2, 0xe0e0e0);
        grid.moveTo(x * 10, 0);
        grid.lineTo(x * 10, h);
    }
    for (var y = 0; y < h / 10; y++) {
        grid.lineStyle(y % 10 ? 1 : 2, 0xe0e0e0);
        grid.moveTo(0, y * 10);
        grid.lineTo(w, y * 10);
    }
    grid.endFill();
    grid.lineStyle(4, 0xe0e0e0);
    grid.drawRect(0, 0, w, h);
    stage.addChild(grid);

    $(buildings).each(function() {
        if(!manifest[this.type].hasOwnProperty('color')) {
            console.log('new color for '+this.type);
            manifest[this.type].color = colorByIndex(p);
            p++;
        }
        console.log(this.type);
        var b = new PIXI.Graphics();
        b.lineStyle(1, 0x000000);
        b.beginFill(manifest[this.type].color);
        b.drawRect(this.x, this.y, manifest[this.type].size_x, manifest[this.type].size_y);
        b.endFill();

        stage.addChild(b);
    });

    renderer.render(stage);

    function colorByIndex(index) {
        var r, g, b;
        r = index & 0x03;
        g = (index >> 2) & 0x03;
        b = (index >> 4) & 0x03;
        var result = (((b * 85) << 0) + ((g * 85) << 8) + ((r * 85) << 16));
        console.log(index + ':' + result.toString(16));
        return result;
    }


</script>
