<?php

class Grupo extends AppModel {
    public $name = 'Grupo';
    public $displayField = 'nome';
    
    var $virtualFields = array(
        'contatos' => 'SELECT COUNT(*) FROM contatos_grupos as ContatoGrupo WHERE ContatoGrupo.grupo_id = Grupo.id'
    );
    
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o nome do grupo.'
            )
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data['Grupo']['nome'] = mb_strtoupper($this->data['Grupo']['nome'], 'UTF-8');
        
        return parent::beforeSave($options);
    }
}