<?php

App::uses('Grupo', 'Model');
App::uses('Categoria', 'Model');
App::uses('Template', 'Model');

class Cliente extends AppModel {
    public $name = 'Cliente';
    public $order = "nome ASC";
    public $displayField = 'nome';
    
    var $virtualFields = array(
        'sms_utilizado' => 'SELECT count(Retorno.id) 
                                FROM retornos as Retorno 
                            where Retorno.cliente_id = Cliente.id and 
                                  month(Retorno.data) = month(now()) and year(Retorno.data) = year(now())'
    );
    
    public $validate = array(
        'nome' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o nome do cliente.'
            )
        ),
        'cnpj' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o CNPJ do cliente.'
            )
        ),
        'sms_mes' => array(
            'rule' => array('notBlank'),
            'message' => 'Favor informar a Qtd de SMS por mês.'
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data[$this->name]['nome']        = mb_strtoupper($this->data[$this->name]['nome'], 'UTF-8');
        $this->data[$this->name]['endereco']    = mb_strtoupper($this->data[$this->name]['endereco'], 'UTF-8');
        $this->data[$this->name]['responsavel'] = mb_strtoupper($this->data[$this->name]['responsavel'], 'UTF-8');
        $this->data[$this->name]['email']       = mb_strtoupper($this->data[$this->name]['email'], 'UTF-8');
        if ( isset( $this->data[$this->name]['logo']['name'] ) && $this->data[$this->name]['logo']['name'] != '') {
            $this->prepararArquivoParaBanco($this->data[$this->name]['logo']);
        } else {
            unset($this->data[$this->name]['logo']);
        }
        
        return parent::beforeSave($options);
    }
    
    public function afterSave($created, $options = array()) {
        
        if($created) {
            $grupoDb     = new Grupo();
            $categoriaDb = new Categoria();
            $templateDb  = new Template();
            
            $categoriaDb->create();
            $categoriaDb->save(array('cliente_id'=>$this->getInsertID(), 'nome'=>'ANIVERSÁRIOS', 'tipo'=>'niver'));
            
            $grupoDb->create();
            $grupoDb->save(array('cliente_id'=>$this->getInsertID(), 'nome'=>'ANIVERSÁRIOS', 'cor'=>'#faff00', 'tipo'=>'niver'));
            
            $templateDb->create();
            $templateDb->save(array('cliente_id'=>$this->getInsertID(), 'mensagem'=>'{NOME}, CONFIGURE AQUI A SUA MENSAGEM DE ANIVERSARIO.', 'tipo'=>'niver'));
        }
        return parent::afterSave($created, $options = array());
    }
    
    private function prepararArquivoParaBanco(&$campo)
    {
        if ($campo) {
            $this->tmp_name_arquivo = $campo['tmp_name'];
            
            $file = new File($campo['tmp_name']);
            
            //$this->data[$this->name]['ds_extensao'] = strtolower(end(explode('.', $this->data[$this->name]['logo']['name'])));
            
            $campo = $file->read();
            
            $file->close();
        }
    }
}