<?php

App::uses('AuthComponent', 'Controller/Component');

class Usuario extends AppModel {
    public $name = 'Usuario';
    public $validate = array(
        'cliente_id' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'O cliente é obrigatório.'
            )
        ),
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'O nome é obrigatório.'
            )
        ),
        'username' => array(
            'required' => array(
                'rule' => array('email', true),
                'message' => 'Favor informar um email para ser o usuário de acesso.'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A senha é obrigatória.'
            )
        ),
        'role' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Perfil inválido.'
            )
        )
    );
    
    public $belongsTo = array(
        'Cliente' => array(
            'className' => 'Cliente',
            'foreignKey' => 'cliente_id'
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data[$this->alias]['nome']   = mb_strtoupper($this->data[$this->alias]['nome'], 'UTF-8');
        if (isset($this->data[$this->alias]['password'])) {
            $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
        }
        return parent::beforeSave($options);
    }
}