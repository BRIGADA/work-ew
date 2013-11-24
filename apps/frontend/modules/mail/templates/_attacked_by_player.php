<div class="modal-header">
  <h4 class="modal-title">Отчёт об атаке <?php echo $message->attacker->name ?> на вас</h4>
</div>
<div class="modal-body">        
  <p><?php echo $message->attacker_base_type ?> &rarr; <?php echo $message->defender_base_type ?></p>
  <table class="table table-condensed table-bordered">
    <tr>
      <th>Result</th>
      <td><?php echo $message->battle_data->outcome ?></td>
    </tr>
    <tr>
      <th>XP</th>
      <td><?php echo $message->battle_data->xp ?></td>
    </tr>
    <!-- 
<tr>
    <th>XP boosted</th>
    <td><?php echo $message->battle_data->boosted_xp ?></td>
</tr>
    -->
    <?php if (isset($message->battle_data->sp)) : ?>
      <tr>
        <th>SP</th>
        <td><?php echo $message->battle_data->sp ?></td>
      </tr>
    <?php endif ?>
    <tr>
      <th>Force</th>
      <td><?php echo $message->battle_data->force_gained ?></td>
    </tr>
    <?php if (count($message->battle_data->backup_generals)) : ?>
      <tr>
        <th>Generals</th>
        <td><?php var_dump($message->battle_data->backup_generals->getRawValue()) ?></td>
      </tr>
    <?php endif ?>
    <tr>
      <th>Crystal</th>
      <td><?php echo $message->battle_data->resources_taken->crystal ?></td>
    </tr>
    <tr>
      <th>Gas</th>
      <td><?php echo $message->battle_data->resources_taken->gas ?></td>
    </tr>
    <tr>
      <th>Energy</th>
      <td><?php echo $message->battle_data->resources_taken->energy ?></td>
    </tr>
    <tr>
      <th>Uranium</th>
      <td><?php echo $message->battle_data->resources_taken->uranium ?></td>
    </tr>
    <tr>
      <th>Units</th>
      <td>
        <?php
        $c = 0;
        $d = array();
        foreach ($message->battle_data->units as $u) {
          $c += $u->deployed;
          $d[] = sprintf('%u x %s L%u - %u', $u->deployed, $u->unit_type, $u->level, $u->dead);
        }
        echo $c;
        ?>
      </td>
    </tr>
  </table>
</div>
