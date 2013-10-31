<?php use_helper('I18N') ?>
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
        <tr data-id="<?php // echo $building['id'] ?>">
            <td><?php // echo $building['id'] ?></td>
            <td><?php // echo __(strtolower($building['type']) . '.name', null, 'ew-buildings') ?></td>
            <td><?php // echo $building['x'] ?></td>
            <td><?php // echo $building['y'] ?></td>
            <td><?php // echo $building['level'] ?></td>
            <td><?php // echo $building['hp'] ?></td>
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
    var ctx = document.getElementById('base').getContext('2d');
    
    function colorByIndex(index) {
        var r, g, b;
        r = index & 0x03;
        g = (index >> 2) & 0x03;
        b = (index >> 4) & 0x03;
        return 'rgb('+r*85+','+g*85+','+b*85+')';
    }

    $(function() {
        

        ctx.strokeStyle = '#e0e0e0';

        ctx.beginPath();
        for (var x = 1; x < w / 10; x++) {
            ctx.moveTo(x * 10, 0);
            ctx.lineTo(x * 10, h);
        }
        for (var y = 1; y < h / 10; y++) {
            ctx.moveTo(0, y * 10);
            ctx.lineTo(w, y * 10);
        }
        ctx.stroke();

        ctx.fillStyle = 'yellow';
        ctx.strokeStyle = 'red';
        ctx.font = '10px Verdana';
        
        $(buildings).each(function() {
            ctx.fillStyle = colorByIndex(manifest[this.type].id);
            
            console.log(this.type + ': ' + ctx.fillStyle);
            ctx.beginPath();
            ctx.rect(this.x, this.y, manifest[this.type].size_x, manifest[this.type].size_y);
            ctx.fill();
            ctx.stroke();
            
            ctx.fillStyle = '#000000';
            ctx.fillText(this.level, this.x+1, this.y+10);
        });

    });

</script>
