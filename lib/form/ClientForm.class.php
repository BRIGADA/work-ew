<?php
class ClientForm extends BaseForm
{
    public function configure()
    {
        $this->widgetSchema['host'] = new sfWidgetFormInputText();
        $this->widgetSchema['host']->setAttribute('class', 'input-xlarge');
        $this->widgetSchema['host']->setAttribute('placeholder', 'null');
        
        $this->widgetSchema['reactor'] = new sfWidgetFormInputText();
        $this->widgetSchema['reactor']->setAttribute('class', 'input-xxlarge');
        $this->widgetSchema['reactor']->setAttribute('placeholder', 'null');
        
        $this->widgetSchema['_session_id'] = new sfWidgetFormInputText();
        $this->widgetSchema['_session_id']->setAttribute('class', 'input-xxlarge');
        $this->widgetSchema['_session_id']->setAttribute('placeholder', 'null');
        
        $this->widgetSchema['user_id'] = new sfWidgetFormInputText();
        $this->widgetSchema['user_id']->setAttribute('class', 'input-small');
        $this->widgetSchema['user_id']->setAttribute('placeholder', 'null');
        
        foreach (array('host', 'reactor', '_session_id', 'user_id') as $key )
        {
            $this->widgetSchema[$key]->setDefault(sfContext::getInstance()->getUser()->getAttribute($key, '', 'playerVO'));
        }
        
        $this->validatorSchema['host'] = new sfValidatorString();
        $this->validatorSchema['reactor'] = new sfValidatorString(array('max_length'=>40, 'min_length'=>40));
        $this->validatorSchema['_session_id'] = new sfValidatorString(array('required'=>false));
        $this->validatorSchema['user_id'] = new sfValidatorInteger();
        
        $this->widgetSchema->setNameFormat('client[%s]');
    }
}