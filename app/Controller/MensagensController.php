<?php

App::uses('HttpSocket', 'Network/Http');

class MensagensController extends AppController {
    
    public $uses = array('Mensagem');

    private function send($a) {
        $url = "http://143.208.9.107/receiving/";    
        $content = json_encode($a,JSON_FORCE_OBJECT);
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER,array("Content-type: application/json"));
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ( $status != 201 ) {
            return json_decode($json_response);
            // echo "Error: call to URL $url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl);
            // return false;
        }


        curl_close($curl);

        $response = json_decode($json_response, true);

        print_r($response);
        return true;
    }
    
    private function execute($mensagem,$f = null) {
        $c = "";
        foreach($mensagem["Grupo"]["Grupo"] as $a) {
            $contact = $this->Mensagem->query("
                select c.telefone from grupos as g inner join contatos_grupos as cg on cg.grupo_id = g.id
                inner join contatos as c on c.id = cg.contato_id
                where g.id = " . $a . ";
            "); 
            foreach($contact as $b) {
                if(preg_match_all("/\s9[0-9]{4}/", $b["c"]["telefone"])) {
                    $c .=  preg_replace("/(\s9|\(|\)|-|\s)/i", "", $b["c"]["telefone"],-1)  . ",";
                } else {
                    $c .=  preg_replace("/(\(|\)|-|\s)/i", "", $b["c"]["telefone"],-1)  . ",";
                }
            }
            $a = array(
                "token" => "123456789",
                "usuario" => $this->Auth->user("nome"),
                "data" => date("Y-m-d H:i:s"),
                "foto" => $f ? "http://centralalertas.com.br/app/webroot/files/ ". $f : null,
                "contatos" => substr($c,0,strlen($c) - 1),
                "mensagem" => $mensagem["Mensagem"]["mensagem"]
            );
        }
        //print_r($this->send($a));
        $this->send($a);
        $this->Session->setFlash('Mensagem enviada com sucesso!');
        $this->redirect('/mensagens');
    }

    public function index() {
        

        $this->Mensagem->recursive = 2;
        $this->paginate = array(
            'limit' => 20
        );
        $conditions = array( 'Mensagem.cliente_id' => $this->Auth->user('cliente_id') );
        
        if ( $this->Auth->user('role') === 'subordinado' ) {
            $conditions['usuario_id'] = $this->Auth->user('id');
        }
        
        if (isset($this->data['Mensagem']['id'])) {            

            // $this->send();
            $mensagem   = $this->data;
            $envios = explode(",", isset($this->data['Mensagem']['envio']) ? $this->data['Mensagem']['envio'] : date("d/m/Y"));
            $uploaddir = ROOT . "/app/webroot/files/ ";
            $uploadfile = $uploaddir . basename($_FILES['data']['name']['Mensagem']['arquivo']);
            foreach ($envios as $envio) {
                $mensagem['Mensagem']['envio'] = $envio . ' ' . (isset($this->data['Mensagem']['hora']) ? $this->data['Mensagem']['hora'] : date("H:i"));
                $mensagem['Mensagem']['arquivo'] = $uploadfile;
                $this->Mensagem->create();
                $this->Mensagem->validator()->remove('Grupo');
                $this->Mensagem->validator()->remove('hora');

                // echo "<pre>";print_r($_FILES);echo "</pre>";exit();
                if ($this->Mensagem->save($mensagem)) {
                    if($_FILES['data']['tmp_name']['Mensagem']['arquivo'] == "") {
                        $this->execute($mensagem);
                        // exit();
                    } else { 
                        if (move_uploaded_file($_FILES['data']['tmp_name']['Mensagem']['arquivo'], $uploadfile)) {
                            $this->execute($mensagem,$_FILES['data']['name']['Mensagem']['arquivo']);
                            // exit();
                            $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
                            $this->redirect('/mensagens');
                        } else {
                            echo "Possible file upload attack!\n";
                        }
                    }
                    // exit();
                } else {
                    $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
                    $this->redirect('/mensagens');
                }
            }
        }
                
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['or'] = array(
                'mensagem like' => '%' . $pesquisa . '%',
                'Categoria.nome like' => '%' . $pesquisa . '%'
            );
            $this->set('pesquisar', $pesquisa);
        }
        $this->set('mensagens', $this->paginate($conditions));
        
        $this->set('categorias', $this->Mensagem->Categoria->find('threaded', array(
            'conditions' => array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null' ),
            'order' => 'nome ASC'
        )));
        
        $conditionsGp = array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null' );
        if ( $this->Auth->user('role') === 'subordinado' ) {
            $conditionsGp['or']   = array('usuario_id'=>$this->Auth->user('id'), 'visivel'=>'1');
        }
        $this->set('grupos', $this->Mensagem->Grupo->find('threaded', array(
            'conditions' => $conditionsGp,
            'order' => 'nome ASC'
        )));
    }
    
    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Mensagem']['id'])) {
            $mensagem   = $this->Mensagem->read('campanha', $this->data['Mensagem']['id']);
            if ($this->Mensagem->delete($this->data['Mensagem']['id'])) {
                if( isset($mensagem['Mensagem']['campanha']) && $mensagem['Mensagem']['campanha'] != '') {
                    $prender_sms = Configure::read('SMS_SERVER_URL') . "holdsms&lgn=" . 
                                   Configure::read('SMS_SERVER_LOGIN') . "&pwd=" . 
                                   Configure::read('SMS_SERVER_SENHA') . "&id=" . 
                                   $mensagem['Mensagem']['campanha'];
                    
                    $HttpSocket = new HttpSocket();
                    $response = $HttpSocket->get($prender_sms);
                }
                $this->Session->setFlash('Registro excluído com sucesso', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Erro ao excluir o registro');
            }
        } else {
            $this->Session->setFlash('Não foi possível excluir o registro');
        }
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    
    public function validaSaldoSms() {
        
        $cliente    = $this->Auth->user('Cliente');
        $limiteMes  = $cliente['sms_mes'];
        
        $validaSaldo = "false";
        $saldos      = array();
        if (isset($this->data['envios']) && isset($this->data['total_contatos']) ) {
            $envios = explode(",", $this->data['envios']);
            foreach ($envios as $envio) {
                $data = explode("/", $envio);
                $mesAno = $data[1] . $data[2];
                if( !isset($saldos[$mesAno]) ) {
                    
                    $find = $this->Mensagem->find('all', array(
                              'fields' => array('sum(total) AS ctotal'), 
                              'conditions'=>array('Mensagem.cliente_id'=>$this->Auth->user('cliente_id'), "DATE_FORMAT(envio, '%m%Y')"=>$mesAno )
                            ));
                    $utilizado  = $find[0][0]['ctotal'];
                    
                    $saldos[$mesAno] = $limiteMes - $utilizado;
                    
                }
                $saldos[$mesAno] -= $this->data['total_contatos'];
            }
            
            $validaSaldo = "true";
            foreach ($saldos as $saldo) {
                if($saldo < 0) {
                    $validaSaldo = "false";
                }
            }
        }
        
        echo $validaSaldo;
        exit ();
    }
    
}

?>