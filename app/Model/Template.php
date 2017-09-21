<?php

class Template extends AppModel {
    public $name = 'Template';
    
    public $validate = array(
        'mensagem' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar a mensagem.'
            )
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data[$this->alias]['mensagem'] = mb_strtoupper($this->data[$this->alias]['mensagem'], 'UTF-8');
        
        return parent::beforeSave($options);
    }
}