<?php use_helper('I18N')?>
<h1><?php echo $result['response']['base']['name'] ?></h1>

<ul>
    <?php foreach ($sf_user->getAttribute('bases', array(), 'player') as $name=>$id): ?>
    <li><a href="<?php echo url_for("common/base?id={$id}") ?>"><?php echo $name ?></a></li>
    <?php endforeach ?>
</ul>
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
    <?php foreach($result['response']['base']['buildings'] as $building) : ?>
    <tr data-id="<?php echo $building['id']?>">
        <td><?php echo $building['id'] ?></td>
        <td><?php echo __(strtolower($building['type']).'.name', null, 'ew-buildings') ?></td>
        <td><?php echo $building['x'] ?></td>
        <td><?php echo $building['y'] ?></td>
        <td><?php echo $building['level'] ?></td>
        <td><?php echo $building['hp'] ?></td>
        <td><button class="btn btn-mini action-upgrade"><i class="icon-arrow-up"></i></button></td>
    </tr>
    <?php endforeach ?>
</table>
<script type="text/javascript">
    $('.action-upgrade').click(function(){
        var base_id = <?php echo $result['response']['base']['id'] ?>;
        var building_id = $(this).closest('tr').data('id');
        
        $.ajax({
            url: '<?php echo url_for('common/REMOTE') ?>',
            data: {
                path: '/api/bases/'+base_id+'/buildings/'+building_id,
                query: {
                    _method: 'put'
                }
            },
            type: 'post',
            success: function(result){
                console.log(result);
            }
        });
        return false;
    });
</script>

<canvas width="<?php echo $result['response']['base']['width'] ?>" height="<?php echo $result['response']['base']['length'] ?>" style="border: 1px solid green;"></canvas>

<?php var_dump($result['response']['base']->getRawValue()) ?>