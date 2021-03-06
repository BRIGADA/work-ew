<div class="page-header">
  <h1 class="clearfix">Справочники <button class="btn btn-primary btn-lg pull-right" id="update">Обновить</button></h1>
</div>

<table class="table table-stripped" id="manifest">
  <tr id="translation-en">
    <th style="max-width: 100px; width: 100px;">EN</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="translation-ru">
    <th>RU</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="buildings">
    <th>buildings</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="items">
    <th>items</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="store">
    <th>store</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="equipment">
    <th>equipment</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="units">
    <th>units</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="campaigns">
    <th>campaigns</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="generals">
    <th>generals</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="skills">
    <th>skills</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="research">
    <th>research</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="defense">
    <th>defense</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="craft">
    <th>craft</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="tokens">
    <th>tokens</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
  <tr id="assets">
    <th>assets</th>
    <td><div class="progress"><div class="progress-bar"></div></div></td>
  </tr>
</table>
<p>

</p>

<script type="text/javascript">
  function compare(a, b) {
    if (typeof b === 'undefined') {
      return false;
    }
    /*    
     if (typeof a !== typeof b) {
     console.log('TYPES NOT EQUAL', typeof a, typeof b);
     return false;
     }
     */
    if (a instanceof Array || a instanceof Object) {
      for (var i in a) {
        if (!b.hasOwnProperty(i)) {
          console.log('new field \'' + i + '\'', a[i]);
          continue;
        }
        if (!compare(a[i], b[i])) {
          console.log('NOT EQUAL');
          console.log(i);
          console.log(a[i], b[i]);
          return false;
        }
      }
      return true;
    }
    if (a != b) {
      console.log('VALUES NOT EQUAL', a, b);

    }
    return a == b;
  }

  $('#update').click(function() {

    $.get('<?php echo url_for('common/REMOTE') ?>', {
      path: '/api/manifest.amf',
      decode: 'amf'
    }, function(manifest) {
      
      var assets = $.map(manifest.assets, function(value, key){
        return {
          file: key,
          hash: value
        };
      });
      
      update('assets', 'Asset', 'file', assets, '<?php echo url_for('manifest/assets')?>');
      
      update('items', 'Item', 'type', manifest.items, '<?php echo url_for('@manifest-items') ?>', function() {
        update('store', 'Store', 'id', manifest.store, '<?php echo url_for('@manifest-store') ?>');
      });
      update('units', 'Unit', 'type', manifest.units, '<?php echo url_for('@manifest-units') ?>');
      update('generals', 'General', 'type', manifest.generals, '<?php echo url_for('@manifest-generals') ?>');
      update('buildings', 'Building', 'type', manifest.buildings, '<?php echo url_for('@manifest-buildings') ?>');
      update('skills', 'Skill', 'type', manifest.skills, '<?php echo url_for('@manifest-skills') ?>');
      update('research', 'Research', 'type', manifest.research, '<?php echo url_for('@manifest-researches') ?>');
      update('defense', 'Defense', 'type', manifest.defense, '<?php echo url_for('@manifest-defenses') ?>');
      update('craft', 'CraftingRecipe', 'name', manifest.crafting_recipes, '<?php echo url_for('@manifest-recipes') ?>');
      update('tokens', 'Token', 'type', manifest.tokens, '<?php echo url_for('@manifest-tokens') ?>');
      
    });

    $.get('<?php echo url_for('common/REMOTE') ?>', {
      path: '/api/manifest/equipment',
      element: 'response/equipment'
    }, function(equipment) {
      update('equipment', 'Equipment', 'type', equipment, '<?php echo url_for('@manifest-equipments') ?>');
    });

    $.get('<?php echo url_for('common/REMOTE') ?>', {
      path: '/api/manifest/campaigns.amf',
      decode: 'amf',
      element: 'campaigns'
    }, function(campaigns) {
      update('campaigns', 'Campaign', 'id', campaigns, '<?php echo url_for('@manifest-campaigns') ?>');
    });

    function update(section, classname, key, actual, fetch, complete) {
      $.get(fetch, function(records) {
        updateRow(0, section, classname, key, actual, records);
        function updateRow(index, section, classname, key, actual, records) {
          if (index >= actual.length) {
            console.log(section + ' complete');
            if (complete instanceof Function) {
              complete();
            }
            return;
          }
          $('#' + section + ' .progress-bar').css('width', ((index + 1) * 100 / actual.length) + '%');
          if (!compare(actual[index], records[actual[index][key]])) {
            $.post('<?php echo url_for('@manifest-update') ?>', {
              value: JSON.stringify(actual[index]),
              class: classname,
              key: key
            }, function() {
              updateRow(index + 1, section, classname, key, actual, records);
            });
          }
          else {
            updateRow(index + 1, section, classname, key, actual, records);
          }
        }
      });
    }

  });

</script>