<?php

class Categoria extends AppModel {
    public $name = 'Categoria';
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o nome da categoria.'
            )
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data['Categoria']['nome'] = mb_strtoupper($this->data['Categoria']['nome'], 'UTF-8');
        
        return parent::beforeSave($options);
    }
}