<?php use_helper('edgeworld') ?>
<?php use_javascript('jquery.lazyload.min.js') ?>
<?php use_javascript('jquery.scrollstop.js') ?>

<div class="page-header">
    <h1>Предметы</h1>
</div>
<p>
    <select id="filter" class="form-control">
        <option value="">&mdash;</option>
        <?php foreach ($tags as $tag) : ?>
            <option value="<?php echo $tag ?>"><?php echo $tag ?></option>
        <?php endforeach ?>
    </select>
</p>

<div class="row" id="items-list">
    <?php foreach ($items as $item): ?>
        <div class="col-md-3 col-lg-3 <?php foreach ($item->tags as $tag): ?> tag-<?php echo $tag ?><?php endforeach ?>">
            <div class="panel panel-default">
                <div class="panel-heading oneline-title">
                    <h3 class="panel-title" title="<?php echo __EW('items', $item->type, 'name') ?>">
                        <a href="<?php echo url_for('@manifest-item?type=' . $item->type) ?>"><?php echo __EW('items', $item->type, 'name') ?></a>
                    </h3>
                </div>
                <div class="panel-body text-center edgeworld-bg" style="min-height: 140px;">
                    <a href="<?php echo url_for('@manifest-item?type=' . $item->type) ?>">
                        <img alt="<?php echo $item->type ?>" style="height: 120px;" src="<?php echo image_path('loading.gif') ?>" data-original="<?php printf('https://kabam1-a.akamaihd.net/edgeworld/images/items/%s', $item->image) ?>">
                    </a>
                </div>

            </div>
            <br>
        </div>
    <?php endforeach; ?>
</div>

<script type="text/javascript">
    $(function() {
        $('#items-list img').lazyload({
            event: "scrollstop"
        });

    });

    $('#filter').change(function() {
        if ($(this).val()) {
            $('#items-list > div.tag-' + $(this).val()).show();
            $('#items-list > div:not(.tag-' + $(this).val() + ')').hide();
        }
        else {
            $('#items-list > div').show();
        }

        $(window).trigger('scrollstop');

    });

<?php if (isset($filter)) : ?>
        $('#filter').val('<?php echo $filter ?>').change();
<?php endif ?>
</script>