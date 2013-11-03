<div class="page-header">
    <h1>Справочники</h1>
    <p class="lead">
        <button class="btn btn-primary" id="fetch">Fetch</button>
        <button class="btn btn-primary" id="manifest">Manifest</button>
        <button class="btn btn-primary" id="update">Update</button>
    </p>
</div>

<table class="table table-stripped">
    <tr id="translation-en">
        <th style="max-width: 100px; width: 100px;">EN</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="translation-ru">
        <th>RU</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="buildings">
        <th>buildings</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="items">
        <th>items</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="equipment">
        <th>equipment</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="">
        <th>units</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>campaigns</th>
        <td>&mdash;</td>
    </tr>
    <tr id="generals">
        <th>generals</th>
        <td><div class="progress"><div class="bar"></div></div></td>
    </tr>
    <tr id="">
        <th>skills</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>research</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>defense</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>craft</th>
        <td>&mdash;</td>
    </tr>
</table>

<script type="text/javascript">
    Array.prototype.compare = function (array) {
        // if the other array is a falsy value, return
        if (!(array instanceof Array))
            return false;

        // compare lengths - can save a lot of time
        if (this.length !== array.length)
            return false;

        for (var i = 0; i < this.length; i++) {
            // Check if we have nested arrays
            if (this[i] instanceof Array && array[i] instanceof Array) {
                // recurse into the nested arrays
                if (!this[i].compare(array[i]))
                    return false;
            }
            else if (this[i] != array[i]) {
                // Warning - two different object instances will never be equal: {x:20} != {x:20}
                return false;
            }
        }
        return true;
    }

    $('#fetch').click(function() {
        $.get('<?php echo url_for('manifest/trans') ?>', function(response) {
            alert(response);
        });
        return false;
    });

    $('#update').click(function() {
        $.get('/uploads/trans.ru.xml', function(xml) {
            console.log(xml);
            var lang = 'ru';
            var t1 = new Date();
            var i = 0;

            function process(element) {
                if (!element) {
                    console.log((new Date() - t1));
                    console.log('i=' + i);
                    return;
                }

                $.post('<?php echo url_for('manifest/translation') ?>', {lang: lang, id: element.tagName, value: element.text}, function() {
                    process(element.nextElementSibling);
                });
            }

            process(xml.documentElement.firstElementChild);

        });

//        $('table.table td').html('<div class="progress"><div class="bar"></div></div>');
        return false;
    });

    $('#manifest').click(function() {
        $.ajax({
            url: '<?php echo url_for('common/REMOTE') ?>',
            data: {
                path: '/api/manifest.amf',
                decode: 'amf',
                proxy: true
            },
            success: function(manifest) {
                var a = [{
                        url: '<?php echo url_for('@manifest-building-update') ?>',
                        element: 'buildings'
                    },
                    {
                        url: '<?php echo url_for('@manifest-general-update') ?>',
                        element: 'generals'
                    },
                    {
                        url: '<?php echo url_for('@manifest-item-update') ?>',
                        element: 'items'
                    }];

                updateBuildings(function(){});
                
                function updateBuildings() {
                    $.get('<?php echo url_for('@manifest-buildings') ?>', function(buildings){
                        
                        function updateBuilding(index) {
                            if(index >= manifest.buildings.length) {
                                console.log('buildings done');
                                return;
                            }
                            var c = manifest.buildings[index];
                            var changed = false;
                            if(!buildings.hasOwnProperty(c.type) || !buildings[c.type].compare(c)) {
                                changed = true;
                            }
                            console.log('changed: '+changed);
                            updateBuilding(index + 1);
                                
                        }
                        console.log(buildings);
                        updateBuilding(0);
                    });
                }
                /*
                function updateBuilding(index) {
                    if (p >= a.length) {
                        console.log('done');
                        return;
                    }

                    var b = a[p];

                    updateElement(0);

                    function updateElement(index) {
                        console.log(b.element);

                        if (index >= manifest[b.element].length) {
                            console.log(b.element + ' done');
                            update(p + 1);
                            return;
                        }

                        $.ajax({
                            url: b.url,
                            data: {
                                value: JSON.stringify(manifest[b.element][index])
                            },
                            type: 'post',
                            success: function(r) {
                                $('#' + b.element + ' .progress > .bar').css('width', ((index + 1) * 100 / manifest[b.element].length) + '%');
//                                return;
                                updateElement(index + 1);
                            },
                            error: function() {
                                console.log('failed to update ' + b.element);
                            }
                        });
                    }
                }
                */

            }
        });
        return false;
    });

</script>