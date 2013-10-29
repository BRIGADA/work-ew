<div class="page-header">
    <h1>Справочники</h1>
    <p class="lead">
        <button class="btn btn-primary" id="fetch">Fetch</button>
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
        <td>&mdash;</td>
    </tr>
    <tr id="items">
        <th>items</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>units</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>campaigns</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>generals</th>
        <td>&mdash;</td>
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
        <th>equipment</th>
        <td>&mdash;</td>
    </tr>
    <tr id="">
        <th>craft</th>
        <td>&mdash;</td>
    </tr>
</table>

<script type="text/javascript">
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
                if(!element) {
                    console.log((new Date() - t1));
                    console.log('i='+i);
                    return;
                }
                
                $.post('<?php echo url_for('manifest/translation') ?>', {lang: lang, id: element.tagName, value: element.text }, function(){
                   process(element.nextElementSibling);
                });
            }
            
            process(xml.documentElement.firstElementChild);
            
        });
        
//        $('table.table td').html('<div class="progress"><div class="bar"></div></div>');
        return false;
    });
</script>