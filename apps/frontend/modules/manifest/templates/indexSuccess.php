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
  <tr id="store">
    <th>store</th>
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
  Array.prototype.compare = function(array) {
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
    $.get('<?php echo url_for('manifest/file') ?>', function(manifest){
      
      updateBuilding(0);
      
      
      function updateItem(index){
        if(index >= manifest.items.length) {
          console.log('items complete');
          updateStore(0);
          return;
        }
        
        $('#items .bar').css('width', (100 * (index+1)/manifest.items.length)+'%')            
        $.ajax({
          type: 'post',
          url: '<?php echo url_for('@manifest-item-update')?>',
          data: manifest.items[index],
          success: function(){
            updateItem(index+1);
          },
          error: function(){
            console.log('error');
          }
        });
      }
      
      function updateStore(index){
        if(index >= manifest.store.length) {
          console.log('store complete');
          return;
        }

        $('#store .bar').css('width', (100 * (index+1)/manifest.store.length)+'%')            
        
        $.ajax({
          type: 'post',
          url: '<?php echo url_for('manifest/storeUpdate')?>',
          data: manifest.store[index],
          success: function(){
            updateStore(index + 1);
          },
          error: function(){
            console.log('error');
          }
        });
      
      }
      
      function updateBuilding(index) {
        if(index >= manifest.buildings.length) {
          console.log('buildings complete');
          updateItem(0);
          return;
        }

        $('#buildings .bar').css('width', (100 * (index+1)/manifest.buildings.length)+'%')            
        
        $.ajax({
          type: 'post',
          url: '<?php echo url_for('manifest/buildingUpdate')?>',
          data: {
            value: JSON.stringify(manifest.buildings[index])
          },
          success: function(){
            updateBuilding(index + 1);
          },
          error: function(){
            console.log('error');
          }
        });
      }
      
    });

    return false;
  });

</script>