<?php

App::uses('HttpSocket', 'Network/Http');

class RotinasController extends AppController {
    
    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('index');
    }
    
    public function index($funcao) {
        set_time_limit(0);
        
        $this->loadModel('Mensagem');
        $this->loadModel('Agendamento');
        $this->loadModel('Grupo');
        $this->loadModel('Categoria');
        $this->loadModel('Contato');
        $this->loadModel('Retorno');
        $this->loadModel('Template');
        
        $this->$funcao();
        
        echo 'TERMINO DA EXECUÇÃO DAS ROTINAS';
        exit ();
    }
    
    /**
     * RETORNO DE CAMPANHA
     */
    private function retornoDeCampanha() {
        $retornoCampanha    = Configure::read('SMS_SERVER_URL') . "getstatus&lgn=" . 
                              Configure::read('SMS_SERVER_LOGIN') . "&pwd=" . 
                              Configure::read('SMS_SERVER_SENHA') . "&id=";
        
        $agora  = new DateTime('now');
        $agora->sub(new DateInterval("PT30M"));
        $mensagem           = $this->Mensagem->find('first', array(
                              'conditions'=>array( "campanha != ''", 'sucesso <= 0', 'erro <= 0', 'envio <='=>$agora->format('Y-m-d H:i:s'))
        ));
        if (isset($mensagem['Mensagem']) && isset($mensagem['Mensagem']['id'])) {
            debug($mensagem['Mensagem']['campanha']);
            $HttpSocket = new HttpSocket();
            $response = $HttpSocket->get($retornoCampanha . $mensagem['Mensagem']['campanha']);
            $sucesso  = 0;
            $erro     = 0;
            
            $retorno  = json_decode($response->body, true);
            $linhas =  preg_split( '/\r\n|\r|\n/', $retorno['data']);

            foreach ($linhas as $linha) {

                if( strlen($linha) > 10 ) {
                    $contato_id = 0;
                    $retornoSms = str_getcsv($linha, ";");
                    $logRetorno['Retorno']['cliente_id']    = $mensagem['Mensagem']['cliente_id'];
                    $logRetorno['Retorno']['mensagem_id']   = $mensagem['Mensagem']['id'];
                    $logRetorno['Retorno']['grupo_id']      = $this->findGrupo($retornoSms[10], $contato_id, $mensagem['Grupo']);
                    $logRetorno['Retorno']['contato_id']    = $contato_id;
                    $logRetorno['Retorno']['campanha']      = $mensagem['Mensagem']['campanha'];
                    $logRetorno['Retorno']['celular']       = $retornoSms[10];
                    $logRetorno['Retorno']['resposta']      = $retornoSms[6];
                    $logRetorno['Retorno']['operadora']     = $retornoSms[9];
                    $logRetorno['Retorno']['data']          = $retornoSms[2];
                    if( strstr(trim($retornoSms[6]), 'SUCESSO') ) {
                        $sucesso++;
                    } else {
                        $erro++;
                    }
                    $this->Retorno->create();
                    $this->Retorno->save($logRetorno);

                }
            }
            $this->Mensagem->id = $mensagem['Mensagem']['id'];
            $atualiza['Mensagem']['total'] = $sucesso + $erro;
            $atualiza['Mensagem']['sucesso'] = $sucesso;
            $atualiza['Mensagem']['erro'] = $erro;
            $this->Mensagem->save($atualiza, array('fieldList'=>array('total','sucesso', 'erro'),'validate'=>false, 'callbacks'=>false) );
            
        }
    }
    
    /**
     * ENVIO DE CAMPANHAS AGENDADAS
     */
    private function envioDeAgendamentos() {
        $agora  = new DateTime('now');
        $agora->add(new DateInterval("PT30M"));
        
        /*$mensagensAgendadas = $this->Mensagem->find('all', array(
            'conditions'=>array( "(campanha is null or campanha = '')", 'envio <='=>$agora->format('Y-m-d H:i:s'))
        ));*/

        $agend = $this->Agendamento->find('all', array(
            'conditions' => array('data_envio <='=> $agora->format('Y-m-d H:i:s'),"(success is null or success <> 1)")
        ));
       // echo "<pre>";print_r($agend);echo "</pre>";
        foreach ($agend as $a) {

            $HttpSocket = new HttpSocket();
         
         // COMENTADO PARA QUE NÃO ENVIE SMS A PEDIDO DO MARCOS NASCIMENTO
         // $response = $HttpSocket->get($a['Agendamento']['url']);

            $this->Agendamento->id = $a['Agendamento']['id'];
            $this->Agendamento->save(['Agendamento'=>['return'=>$response->body,'success'=>strripos($response->body, '<sucesso>false</sucesso>')?0:1]], ['fieldList'=>['return','success'],'validate'=>false, 'callbacks'=>false]);

        }

        /*foreach ($mensagensAgendadas as $mensagem) {
        
            $envio_sms  = Configure::read('SMS_SERVER_URL') . "SendSMS&username=" . 
                          Configure::read('SMS_SERVER_LOGIN') . "&password=" . 
                          Configure::read('SMS_SERVER_SENHA') . "&text=" . 
                          $mensagem['Mensagem']['mensagem'] . "&to=";
            
            $grupos = "";
            foreach ($mensagem['Grupo'] as $grupo) {
                if($grupos != "") {
                    $grupos .= ",";
                }
                $grupos .= $grupo['id'];
            }
            // echo "<pre>";print_r($grupos);echo "</pre>";
            if(trim($grupos) <> "" ) { 
                $celulares  = "";
                $contatos   = $this->Contato->find('all', array('conditions'=>array('
                    Contato.id in( SELECT DISTINCT contato_id from contatos_grupos where grupo_id in(' . $grupos . ') )
                ')));
                foreach ($contatos as $contato) {
                    if($contato['Contato']['telefone'] != '' && strlen($contato['Contato']['telefone']) > 10 ) {
                        if($celulares != "") {
                            $celulares .= ",";
                        }
                        $celulares .= preg_replace("/[^0-9]/", "", $contato['Contato']['telefone']);

                    }
                }
                $envio_sms .= $celulares;
                
                $HttpSocket = new HttpSocket();
                $response = $HttpSocket->get($envio_sms);
                
                echo "<pre>";print_r($envio_sms);echo "</pre>";
                echo "<pre>";print_r($response);echo "</pre>";
                $retorno  = json_decode($response->body, true);
                $this->Mensagem->id = $mensagem['Mensagem']['id'];
                $campanha['Mensagem']['campanha'] = $retorno['data'];
                $this->Mensagem->save($campanha, array('fieldList'=>array('campanha'),'validate'=>false, 'callbacks'=>false) );
            }
        }*/
    }
    
    /**
     * SMS ANIVERSARIANTES
     */
    private function envioSmsAniversariante() {
        $agora  = new DateTime('now');
        
        $aniversariantes    = $this->Contato->find('all', array(
                              'conditions'=>array( "DATE_FORMAT(aniversario, '%m-%d')" => $agora->format('m-d'))
        ));
        
        foreach ($aniversariantes as $contato) {
            debug($contato);
        
            $mensagem['Mensagem']['cliente_id']   = $contato['Contato']['cliente_id'];
            $mensagem['Mensagem']['usuario_id']   = $contato['Contato']['usuario_id'];
            $mensagem['Contato']['id']            = $contato['Contato']['id'];
            $mensagem['Mensagem']['total']        = 1;
            $mensagem['Mensagem']['categoria_id'] = $this->getIdCategoria('niver', $contato['Contato']['cliente_id']);
            $mensagem['Mensagem']['mensagem']     = $this->getMensagemAniversario($contato['Contato']);
            //$agora->add(new DateInterval("PT60M"));
            $mensagem['Mensagem']['envio']        = $agora->format('Y-m-d H:i:s');
            $mensagem['Grupo']['Grupo'][0]        = $this->getIdGrupo('niver', $contato['Contato']['cliente_id']);
            
            $this->Mensagem->create();
            $this->Mensagem->save($mensagem);

        }
    }
    
    private function findGrupo($celular, &$contato_id, $grupos) {
        $contato    = $this->Contato->find('first', array(
                              'conditions'=>array( 'trim(replace(replace(replace(replace(telefone, "(", ""), ")", ""), " ", ""), "-", ""))'=>$celular )
            ));
        if(isset($contato['Contato'])) {
            $contato_id = $contato['Contato']['id'];
        } else {
            debug($celular);
        }
        
        foreach ($grupos as $grupo) {
            if($grupo['tipo'] != '') {
                return $grupo['id'];
            }
            $contatoGrupo   = $this->Grupo->query('SELECT * FROM `contatos_grupos` WHERE grupo_id = ' . $grupo['id'] . ' AND contato_id = ' . $contato_id);
            if(isset($contatoGrupo[0]) && isset($contatoGrupo[0]['contatos_grupos']) && $contatoGrupo[0]['contatos_grupos']['grupo_id'] > 0) {
                return $contatoGrupo[0]['contatos_grupos']['grupo_id'];
            }
        }
        debug('Grupo não encontrado.');
        return 0;
    }
    
    private function getIdCategoria($tipo, $cliente_id) {
        $grupo  = $this->Categoria->find('first', array(
                              'conditions' => array( 'tipo'=>$tipo, 'cliente_id'=>$cliente_id )
            ));
        return $grupo['Categoria']['id'];
    }
    
    private function getIdGrupo($tipo, $cliente_id) {
        $grupo  = $this->Grupo->find('first', array(
                              'conditions' => array( 'tipo'=>$tipo, 'cliente_id'=>$cliente_id )
            ));
        return $grupo['Grupo']['id'];
    }
    
    private function getMensagemAniversario($contato) {
        $template   = $this->Template->find('first', array(
                              'conditions' => array( 'tipo'=>'niver', 'cliente_id'=>$contato['cliente_id'] )
            ));
        return str_replace('{NOME}', $contato['nome'], $template['Template']['mensagem']);
    }
    
    /**
     * RELATÓRIO DIÁRIO
     */
    private function envioRelatorioDiario() {
        App::uses('HtmlHelper', 'View/Helper');
        $html   = new HtmlHelper(new View());
        
        $this->loadModel('Cliente');
        $this->loadModel('Usuario');
        
        $agora  = new DateTime('now');
        
        $titulo     = 'RELATÓRIO DIÁRIO DE UTILIZAÇÃO: ' . $agora->format('d/m/Y');
        $mensagem   = $html->tag('h1', $titulo);
        
        $clientes   = $this->Cliente->find('all');
        foreach ($clientes as $cliente) {
            $mensagem  .= $html->tag('br');            
            $mensagem  .= $html->tag('h2', 'Cliente: ' . $cliente['Cliente']['nome']);
            
            
            /** LOGIN */
            $mensagem  .= $html->tag('table', null, array('cellspacing'=>10));
            $mensagem  .= $html->tableHeaders(array(array('ÚLTIMO LOGIN' => array('colspan'=>'2'))));
            $usuarios   = $this->Usuario->find('all', array(
                                  'conditions'=>array( 'cliente_id' => $cliente['Cliente']['id'],  "DATE_FORMAT(last_login, '%Y-%m-%d')" => $agora->format('Y-m-d'))
            ));
            $contador   = 0;
            foreach ($usuarios as $usuario) {
                $contador++;
                $mensagem  .= $html->tableCells(array($usuario['Usuario']['last_login'],$usuario['Usuario']['nome']));
            }
            if($contador == 0) {
                $mensagem  .= $html->tableCells(array('Cliente não acessou o sistema nesta data.'));
            }
            $mensagem  .= $html->tag('/table');
            
            /** MENSAGENS */
            $mensagem  .= $html->tag('table', null, array('cellspacing'=>10));
            $mensagem  .= $html->tableHeaders(array(array('RESUMO DAS MENSAGENS NO DIA' => array('colspan'=>'4'))));
            
            $msgCadastradas = $this->Mensagem->find('count', array(
                                  'conditions'=>array( 'Mensagem.cliente_id' => $cliente['Cliente']['id'],  "DATE_FORMAT(Mensagem.created, '%Y-%m-%d')" => $agora->format('Y-m-d'))
            ));
            $msgEnviadas    = $this->Mensagem->find('first', array(
                                  'fields' => array('sum(total) AS total', 'sum(sucesso) AS sucesso', 'sum(erro) AS erro'),
                                  'conditions'=>array( 'Mensagem.cliente_id' => $cliente['Cliente']['id'],  "DATE_FORMAT(Mensagem.envio, '%Y-%m-%d')" => $agora->format('Y-m-d'))
            ));

            if ($msgCadastradas > 0 || $msgEnviadas[0]['total'] > 0 || $msgEnviadas[0]['sucesso'] > 0 || $msgEnviadas[0]['erro'] > 0) {
                $mensagem  .= $html->tableHeaders(array('Cadastradas', 'Envio SMS', 'Sucesso', 'Erro'));
                $mensagem  .= $html->tableCells(array($msgCadastradas,$msgEnviadas[0]['total'], $msgEnviadas[0]['sucesso'], $msgEnviadas[0]['erro']));
            }
            else {
                $mensagem  .= $html->tableCells(array( array( array ('Cliente não cadastrou mensagens e não enviou SMS nesta data.', array('colspan'=>'4') ))));
            }
            $mensagem  .= $html->tag('/table');
            
            
            /** CONTATOS */
            $mensagem  .= $html->tag('table', null, array('cellspacing'=>10));
            $mensagem  .= $html->tableHeaders(array(array('RESUMO DOS CONTATOS NO DIA' => array('colspan'=>'2'))));
            
            $contAdd    = $this->Contato->find('count', array(
                                  'conditions'=>array( 'Contato.cliente_id' => $cliente['Cliente']['id'],  "DATE_FORMAT(Contato.created, '%Y-%m-%d')" => $agora->format('Y-m-d'))
            ));
            $contUpd    = $this->Contato->find('count', array(
                                  'conditions'=>array( 'Contato.cliente_id' => $cliente['Cliente']['id'],  "DATE_FORMAT(Contato.modified, '%Y-%m-%d')" => $agora->format('Y-m-d'))
            ));

            if ($contAdd > 0 || $contUpd > 0) {
                $mensagem  .= $html->tableHeaders(array('Novos', 'Atualizados'));
                $mensagem  .= $html->tableCells(array($contAdd, $contUpd));
            }
            else {
                $mensagem  .= $html->tableCells(array( array( array ('Cliente não cadastrou e não atualizou Contatos nesta data.', array('colspan'=>'2') ))));
            }
            $mensagem  .= $html->tag('/table');
            
            
            $mensagem  .= $html->tag('hr');
        }
        
        $this->enviarRelatorio($titulo, $mensagem);
    }
    
    private function enviarRelatorio($assunto, $mensagem) {
        App::uses('CakeEmail', 'Network/Email');
        $Email = new CakeEmail('smtp');
        
        $Email->to('marcos.brasilia@hotmail.com');
        $Email->cc('neygodoy@gmail.com');
        $Email->subject('[CLIENTE AVISOS] - ' . $assunto);
        $Email->send($mensagem);
    }
    
}

?>