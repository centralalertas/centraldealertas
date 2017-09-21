<?php

class Contato extends AppModel {
    public $name = 'Contato';
    public $order = "nome ASC";
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o nome do contato.'
            )
        ),
        'sobrenome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o sobrenome do contato.'
            )
        ),
        'telefone' => array(
            'rule' => array('notBlank'),
            'message' => 'Favor informar o telefone do contato.'
        ),
        'email' => array(
            'rule' => array('email', true),
            'message' => 'Favor informar um email válido.',
            'allowEmpty' => true
        ),
        'Grupo' => array(
            'multiple' => array(
                'rule' => array('multiple', array('min' => 1)),
                'message' => 'Selecione ao menos um Grupo',
                'required' => true
            ),
        )
    );
    
    public $hasAndBelongsToMany = array(
        'Grupo' => array(
            'className' => 'Grupo',
            'joinTable' => 'contatos_grupos',
            'foreignKey' => 'contato_id',
            'associationForeignKey' => 'grupo_id',
            'unique' => 'keepExisting'
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data['Contato']['nome']      = mb_strtoupper($this->data['Contato']['nome'], 'UTF-8');
        $this->data['Contato']['sobrenome'] = mb_strtoupper($this->data['Contato']['sobrenome'], 'UTF-8');
        if (empty($this->data['Contato']['nascimento'])) {
            $this->data['Contato']['nascimento'] = null;
        }
        
        foreach (array_keys($this->hasAndBelongsToMany) as $model){
            if(isset($this->data[$this->name][$model])){
                $this->data[$model][$model] = $this->data[$this->name][$model];
                unset($this->data[$this->name][$model]);
            }
        }
        
        return parent::beforeSave($options);
    }
}