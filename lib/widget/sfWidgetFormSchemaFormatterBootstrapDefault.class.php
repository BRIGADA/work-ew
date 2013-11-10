<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class sfWidgetFormSchemaFormatterBootstrapDefault extends sfWidgetFormSchemaFormatter {
  protected $rowFormat = '<div class="form-group">%label%%field%%error%</div>%hidden_fields%';
  protected $decoratorFormat = '';
}