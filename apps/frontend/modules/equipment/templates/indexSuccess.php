<?php use_helper('I18N') ?>
<?php use_javascript('jquery.sortElements.js') ?>
<?php use_javascript('bootbox.min.js') ?>
<?php use_javascript('http://code.highcharts.com/highcharts.js') ?>

<div class="page-header">
    <h1><?php echo __('Equipment') ?></h1>
</div>

<audio src="<?php echo url_for('/buzz.ogg') ?>" preload="auto" id="sound-error"></audio>

<p>
    <button id="action-upgrade-all" class="btn"><i class="icon-arrow-up"></i> Обновить</button>
    <!-- <button id="action-delete-all" class="btn"><i class="icon-trash"></i> Удалить</button> -->
    <button id="action-repair-all" class="btn"><i class="icon-refresh"></i> Починить</button>
    <button id="action-craft" class="btn"><i class="icon-gift"></i> Крафтить</button>
</p>

<table class="table table-hover table-condensed" id="equipment-list">
    <thead>
        <tr>
            <td>
                <select id="filter-stats" multiple="multiple" size="6" class="input-block-level">
                    <?php foreach ($stats as $stat) : ?>
                        <option value="<?php echo $stat ?>"><?php echo $stat ?></option>
                    <?php endforeach ?>
                </select>
            </td>
            <td style="width: 40px">
                <select id="filter-level" multiple="multiple" size="6" style="width: 40px;">
                    <?php foreach ($levels as $level) : ?>
                        <option><?php echo $level ?></option>
                    <?php endforeach ?>
                </select>
            </td>
            <td style="width: 80px;">
                <select id="filter-durability" size="6" style="width: 80px;">
                    <option value="-1">&mdash;</option>
                    <option value="0">Сломан</option>
                    <option value="1">Целый</option>
                </select>
            </td>
            <td style="width: 60px">
                <select id="filter-equipped" size="6" style="width: 60px">
                    <option value="-1">&mdash;</option>
                    <option value="1">Да</option>
                    <option value="0">Нет</option>
                </select>
            </td>
            <td style="width: 40px;">
                <select id="filter-tier" size="6" multiple="multiple" style="width: 40px;">
                    <?php foreach ($tiers as $tier) : ?>
                        <option><?php echo $tier ?></option>
                    <?php endforeach ?>
                </select>
            </td>
            <td style="width: 100px;">					    <button class="btn btn-mini" id="hide-unusial" title="Одним нажатием скрываются все элементы, которые содержат 'необычные' характеристики">Скрыть кошер</button>
            </td>
        </tr>
        <tr>
            <th>type</th>
            <th>level</th>
            <th>healh</th>
            <th>used</th>
            <th>tier</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): ?>
            <tr data-id="<?php echo $row->id ?>" data-type="<?php echo $row->type ?>">
                <td><a href="<?php echo url_for("@manifest-equipment?type={$row->type}") ?>"><?php echo __(strtolower($row->type) . '.name', array(), 'ew-items') ?></a></td>
                <td class="cell-level"><?php echo $row->level ?></td>
                <td class="cell-durability"><?php echo $row->durability ?></td>
                <td class="cell-equipped"><?php if ($row->equipped) : ?>+<?php else : ?>-<?php endif ?></td>
                <td class="cell-tier"><?php echo $manifest[$row->type]['levels'][$row->level]['tier'] ?></td>
                <td>
                    <a href="#" class="btn btn-mini action-trash"><i class="icon-trash"></i></a>
                    <a href="#" class="btn btn-mini action-upgrade"><i class="icon-arrow-up"></i></a>
                    <a href="#" class="btn btn-mini action-repair"><i class="icon-refresh"></i></a>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="<?php echo 6 + count($stats) ?>"></th>
        </tr>
    </tfoot>
</table>

<ul class="thumbnails" id="charts">
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
    <li class="span2" data-success="0" data-error="0"><div class="thumbnail" style="height: 160px;"></div></li>
</ul>

<script type="text/javascript">
    var manifest = <?php echo json_encode($manifest->getRawValue(), JSON_NUMERIC_CHECK) ?>;
    var stats = <?php echo json_encode($stats->getRawValue()) ?>;

    var fails = 0;

    /* this stats are ignoring on the deleting or crafting items */
    var stats_usial = ['hp', 'range', 'attack_rate', 'concussion_effect', 'damage', 'simultaneous_targets', 'splash_radius'];

    function checkUnusial(e) {
        var stats = manifest[$(e).data('type')].levels[parseInt($(e).children('.cell-level').text())].stats;
        for (var i in stats) {
            if (stats_usial.indexOf(i) == -1 && stats[i] !== 'false' && stats[i]) {
                console.log(i);
                return true;
            }
        }
        return false;
    }

    $(function() {
        $('#hide-unusial').click(function() {
            $('#filter-stats').val($('#filter-stats > option').map(function() {
                if (stats_usial.indexOf($(this).val()) == -1)
                    return $(this).val();
            }).get()).change();
        });

        function updateCounter() {
            $('#equipment-list > tfoot th').text($('#equipment-list > tbody > tr:visible').size() + ' / ' + $('#equipment-list > tbody > tr').size());
        }

        updateCounter();

        $('#action-cheat').click(function() {
            var set = $('#equipment-list > tbody > tr:visible');
            fails = 0;

            function cheat(index) {
                nodeUP(set.eq(index), function() {
                    index++;
                    if (index >= set.size())
                        index = 0;
                    cheat(index);
                });
            }
            up(0);
        });

        $('#filter-type, #filter-level, #filter-durability, #filter-equipped, #filter-tier, #filter-stats').change(function() {
            $('#equipment-list > tbody').trigger('update-view');
        });

        $('#equipment-list > tbody').on('update-view', function() {
            /*
             $(this).children('tr').sortElements(function(a, b){
             var l1 = parseInt($(a).children('.cell-level').text());
             var l2 = parseInt($(b).children('.cell-level').text());
             
             if(l1 == l2) return parseInt($(a).data('id')) > parseInt($(b).data('id')) ? 1 : -1;
             return  l1 > l2 ? 1 : -1;
             });
             */

            $(this).children('tr').each(function() {
                var v = true;
                if ($('#filter-type').val() && ($('#filter-type').val().indexOf($(this).data('type')) == -1)) {
                    v = false;
                }
                if ($('#filter-level').val() && ($('#filter-level').val().indexOf($(this).children('.cell-level').text()) == -1)) {
                    v = false;
                }

                if ($('#filter-tier').val() && ($('#filter-tier').val().indexOf(String(manifest[$(this).data('type')].levels[parseInt($(this).children('.cell-level').text())].tier)) == -1)) {
                    v = false;
                }
                var b = $('#filter-stats').val();
                if (b) {
                    for (var i in b) {
                        var e = (manifest[$(this).data('type')].levels[parseInt($(this).children('.cell-level').text())].stats[b[i]]);
                        if (e) {
                            v = false;
                            break;
                        }
                    }
                }

                switch ($('#filter-durability').val()) {
                    case '0':
                        if (parseInt($(this).children('.cell-durability').text()) != 0)
                            v = false;
                        break;
                    case '1':
                        if (parseInt($(this).children('.cell-durability').text()) == 0)
                            v = false;
                        break;
                }

                switch ($('#filter-equipped').val()) {
                    case '0':
                        if ($(this).children('.cell-equipped').text() == '+')
                            v = false;
                        break;
                    case '1':
                        if ($(this).children('.cell-equipped').text() == '-')
                            v = false;
                        break;
                }

                $(this).toggle(v);
            });

            updateCounter();

        }).trigger('update-view');

        $('#action-delete-all').click(function() {
            var set = $('#equipment-list > tbody > tr:visible');
            $.ajax({
                url: '<?php echo url_for('equipment/multidestroy') ?>',
                data: {
                    ids: set.map(function() {
                        return $(this).data('id');
                    }).get()
                },
                success: function(answer) {
                    set.remove();
                    updateCounter();
                    bootbox.alert('Успешно удалено');
                },
                error: function() {
                    bootbox.alert('Ошибка удаления');
                }
            });
            return false;
        });

        $('#action-upgrade-all').click(function() {
            function upgrade() {
                var set = $('#equipment-list > tbody > tr:visible');
                for (var i = 0; i < set.length; i++) {
                    if (parseInt(set.eq(i).children('.cell-durability').text()) != 0) {
                        nodeUpgrade(set.eq(i), function() {
                            upgrade();
                        });
                        return;
                    }
                }

                $('#sound-error').get(0).play();
                bootbox.alert('Обновление завершено.');
            }
            upgrade();
            return false;
        });

        function nodeUpgrade(node, callback) {
            callback = callback || function() {
            };

            node.removeClass().addClass('info');
            $.ajax({
                url: '<?php echo url_for('equipment/upgrade') ?>',
                data: {
                    id: node.data('id')
                },
                type: 'post',
                success: function(response) {
                    var level = parseInt(node.children('.cell-level').text());
                    var chart = $('#charts li').eq(level > 6 ? 5 : (level - 1));

                    node.removeClass();

                    if (response.successful) {
                        node.addClass('success');
                        chart.data('success', chart.data('success') + 1);
                    }
                    else {
                        node.addClass('error');
                        chart.data('error', chart.data('error') + 1);
                    }

                    chart.children('div').highcharts({
                        chart: {
                            animation: false,
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false
                        },
                        tooltip: {
                            pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y})'
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: null
                        },
                        plotOptions: {
                            pie: {
                                animation: false,
                                dataLabels: {
                                    enabled: false
                                }
                            }
                        },
                        series: [{
                                type: 'pie',
                                data: [chart.data('success'), chart.data('error')]
                            }]
                    });

                    node.children('.cell-durability').text(response.equipment.durability);
                    node.children('.cell-level').text(response.equipment.level);
                },
                error: function() {
                    node.children('.cell-durability').text(0);
                    node.removeClass().addClass('warning');
                },
                complete: function() {
                    callback();
                }
            });
        }

        function nodeRepair(node, callback) {
            callback = callback || function() {
            };

            if (node.data('repairing')) {
                callback();
            }

            node.removeClass().addClass('info');
            $.post('<?php echo url_for('equipment/repair') ?>', {id: node.data('id')}, function(response) {
                console.log(response);
                node.data('repairing', true);
                node.addClass('muted');
                callback();
            });
        }

        $('.action-repair').click(function() {
            nodeRepair($(this).closest('tr'));
            return false;
        });

        $('.action-upgrade').click(function() {
            nodeUpgrade($(this).closest('tr'));
            return false;
        });

        $('#action-craft').click(function() {

            var need_confirm = false;
            var set1 = $('#equipment-list > tbody > tr:visible .cell-tier:contains(1)').closest('tr').each(function() {
                need_confirm = need_confirm | checkUnusial(this);
            });
            var set2 = $('#equipment-list > tbody > tr:visible .cell-tier:contains(2)').closest('tr').each(function() {
                need_confirm = need_confirm | checkUnusial(this);
            });

            if (need_confirm) {
                bootbox.confirm('Сейчас будет выполнена операция над редкими элементами. Уверены?', function(result) {
                    if (result) {
                        doCraft();
                    }
                });
            }
            else {
                console.log('Редких компонентов нет');
                doCraft();
            }


            function doCraft() {
                var crafted = 0;
                craft(set1, 10, 'rarebox1a', function() {
                    craft(set2, 5, 'rarebox1b', function() {
                        bootbox.alert('Преробразование деталей завершено! Сделано ' + crafted);
                    });
                });

                function craft(items, count, type, callback) {
                    console.log('enter craft()');
                    if (items.length < count) {
                        console.log('items.length < count');
                        callback();
                        return;
                    }
                    var current = items.splice(0, count);

                    crafted++;

                    $(current).removeClass().addClass('info');

                    $.ajax({
                        url: '<?php echo url_for('equipment/craft') ?>',
                        data: {
                            name: type,
                            ids: $(current).map(function() {
                                return $(this).data('id');
                            }).get()
                        },
                        success: function(response) {
                            console.log(response);
                            $(current).remove();
                            updateCounter();
                            craft(items, count, type, callback);
                        },
                        error: function() {
                            console.log('error');
                        }
                    });
                }
            }
        });

        $('#action-repair-all').click(function() {
            var set = $('#equipment-list > tbody > tr:visible');

            function repair(index) {
                if (index >= set.length) {
                    bootbox.alert('Починка завершена!');
                    return;
                }
                var r = set.eq(index);
                if (r.data('repairing') || parseInt(r.children('.cell-durability').text()) == 1000) {
                    repair(index + 1);
                    return;
                }
                r.data('repairing', true);
                r.removeClass().addClass('muted');
                $.post('<?php echo url_for('equipment/repair') ?>', {id: r.data('id')}, function() {
                    repair(index + 1);
                });
            }

            repair(0);
            return false;
        });

        $('#action-autoequipment-start').click(function() {
            $.ajax({
                url: '<?php echo url_for('common/clientProxy') ?>',
                data: {
                    query: {
                        cmd: 'autoequipment_start',
                        user_id: <?php echo $sf_user->getAttribute('user_id', null, 'player/data') ?>
                    }
                },
                success: function(result) {
                    console.log(result);

                }
            });
            return false;
        });
        $('#action-autoequipment-stat').click(function() {
            $('#charts').show();
            $.ajax({
                url: '<?php echo url_for('common/clientProxy') ?>',
                data: {
                    query: {
                        cmd: 'autoequipment_stat',
                        user_id: <?php echo $sf_user->getAttribute('user_id', null, 'player/data') ?>
                    },
                    type: 'application/json'
                },
                success: function(result) {
                    $('#charts li').data('success', 0).data('error', 0);
                    for (var i in result) {
                        var level = parseInt(i);
                        var chart = $('#charts li').eq(level > 6 ? 5 : (level - 1));
                        chart.data('success', chart.data('success') + result[i].done);
                        chart.data('error', chart.data('error') + result[i].fail);

                        console.log('level: ' + level + ', done: ' + chart.data('success') + ', fail: ' + chart.data('error'));
                    }
                    $('#charts li').each(function() {
                        $(this).children('div').highcharts({
                            chart: {
                                animation: false,
                                plotBackgroundColor: null,
                                plotBorderWidth: null,
                                plotShadow: false
                            },
                            tooltip: {
                                pointFormat: '<b>{point.percentage:.1f}%</b> ({point.y})'
                            },
                            credits: {
                                enabled: false
                            },
                            title: {
                                text: null
                            },
                            plotOptions: {
                                pie: {
                                    animation: false,
                                    dataLabels: {
                                        enabled: false,
//                                                        format: '{point.y}'
                                    }
                                }
                            },
                            series: [{
                                    type: 'pie',
                                    data: [$(this).data('success'), $(this).data('error')]
                                }]
                        });

                    });

                    console.log(result);

                }
            });
            return false;
        });

    });

</script>

