<?php

App::uses('HttpSocket', 'Network/Http');
App::uses('Contato', 'Model');
App::uses('Agendamento', 'Model');

class Mensagem extends AppModel {
    
    public $name 		= 'Mensagem';
    public $order 		= "envio DESC";
    public $useTable 	= 'mensagens';	
    public $primaryKey  = 'id';
    
    public $validate = array(
        'categoria_id' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar a Categoria da Mensagem.'
            )
        ),
        'mensagem' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar o conteúdo da Mensagem.'
            )
        ),
        'envio' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar a data de envio da Mensagem.'
            )
        ),
        'hora' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Favor informar a hora do envio da Mensagem.'
            )
        )
    );
    
    public $hasAndBelongsToMany = array(
        'Grupo' => array(
            'className' => 'Grupo',
            'joinTable' => 'mensagens_grupos',
            'foreignKey' => 'mensagem_id',
            'associationForeignKey' => 'grupo_id',
            'unique' => 'keepExisting'
        ),
    );

    public $hasMany = array(
        'Agendamento' => array(
            'className' => 'Agendamento',
            'joinTable' => 'mensagens',
            'foreignKey' => 'mensagem_id'
        )
    );
    
    public $belongsTo = array(
        'Categoria' => array(
            'className' => 'Categoria',
            'foreignKey' => 'categoria_id'
        )
    );
    
    public function beforeSave($options = array())
    {
        $this->data['Mensagem']['mensagem'] = mb_strtoupper($this->data['Mensagem']['mensagem'], 'UTF-8');

        if (empty($this->data['Mensagem']['envio'])) {
            $this->data['Mensagem']['envio'] = date('d/m/Y');
        }
        
        foreach (array_keys($this->hasAndBelongsToMany) as $model){
            if(isset($this->data[$this->name][$model])){
                $this->data[$model][$model] = $this->data[$this->name][$model];
                unset($this->data[$this->name][$model]);
            }
        }
        
        return parent::beforeSave($options);
    }
    
    // Padrão de envio de SMS criando um array de resultado - BFT
    /**
     * @param bool $created
     * @param array $options
     */
    public function afterSave($created, $options = array()) {

        if($created) {  // Envio do SMS
            $tipoServico        = 'SendSMS';
            $envioSMS           = '';
            $conditionsContato  = array();
            $contador           = 0;
            $contatoDb          = new Contato();

            if( isset($this->data['Contato']) && $this->data['Contato']['id'] > 0 ) {
                $conditionsContato  = array('Contato.id'=>$this->data['Contato']['id']);
            }
            else {
                $grupos = "";
                foreach ($this->data['Grupo']['Grupo'] as $grupo) {
                    if($grupos != "") {
                        $grupos .= ",";
                    }
                    $grupos .= $grupo;
                }
                $conditionsContato  = array('Contato.id in( SELECT DISTINCT contato_id from contatos_grupos where grupo_id in(' . $grupos . ') )');
            }
            $contatos   = $contatoDb->find('all', array('conditions'=>$conditionsContato));

            $HttpSocket = new HttpSocket();

            foreach ($contatos as $contato) {

/*

                // API - GTI_SMS
                $envioSMS = Configure::read('GTI_SERVER_URL').'?'.
                    'user='.Configure::read('GTI_SERVER_LOGIN').
                    '&senha='.Configure::read('GTI_SERVER_SENHA').
                    '&n='.preg_replace("/[^0-9]/", "", $contato['Contato']['telefone'] ).
                    '&msg='.$this->data['Mensagem']['mensagem'];

*/

                // API - BFT
                
                $envioSMS = Configure::read('SMS_SERVER_URL').$tipoServico.
                            '&username='.Configure::read('SMS_SERVER_LOGIN').
                            '&password='.Configure::read('SMS_SERVER_SENHA').
                            '&to='.preg_replace("/[^0-9]/", "", $contato['Contato']['telefone'] ).
                            '&text='.$this->data['Mensagem']['mensagem'];

                //sleep(2);

                /*$response = $HttpSocket->get(  $envioSMS );
                $retorno  = json_decode( $response->body, true );*/
                
                $this->id = $this->data['Mensagem']['id'];
                // $campanha['Mensagem']['campanha'] = $retorno['data'];

                //debug( date('h:i:s') .'  '.$envioSMS );

                //echo "<pre>";print_r($this->data['Mensagem']['envio']);echo "</pre>";
                //echo "<pre>";print_r($this->data['Mensagem']);echo "</pre>";exit();

                // $this->loadModel("Agendamento");
                $agendamento = new Agendamento();
                $data = array("url" => $envioSMS,"data_envio" => $this->data['Mensagem']['envio'],"mensagem_id" => $this->data['Mensagem']['id']);
                $bool = $agendamento->save($data);
                // echo "<pre>";print_r($this->data);echo "</pre>";
                $this->data["Agendamento"] = $bool;
                /*echo "<pre>";print_r($this->data);echo "</pre>";exit();
                $insert = $this->Agendamento->query("
                    insert into agendamento (url,data_envio,mensagem_id)
                    value ('{$envioSMS }','{$this->data['Mensagem']['envio']}',{$this->data['Mensagem']['id']});
                ");
                exit();*/
            }

            $this->save($this->data, array('validate'=>false, 'callbacks'=>false) );

            //exit();
        }
        return parent::afterSave( $created, $options = array() );
    }

}


		