<?php

class ClientesController extends AppController {
    
    public $seguimentos = array(
            'Auto Escola'  	=> 'Auto Escola',
            'Católico' 		=> 'Católico',
            'Evangélico' 	=> 'Evangélico',
            'Política' 		=> 'Política',
            'Empresa' 		=> 'Empresa'
        );
    
    public function index() {
        
        $this->carregaGraficoSmsPorSemana();
        $this->carregaGraficoTotalSmsMes();
        
        $this->Cliente->recursive = 2;
        $this->paginate = array(
            'limit' => 20
        );
        $conditions = array();
        
        if ($this->request->is('post') && isset($this->data['Cliente']['id'])) {
            $this->Cliente->create();
            if ($this->Cliente->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
                
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['or'] = array(
                'nome like' => '%' . $pesquisa . '%',
                'cnpj like' => '%' . $pesquisa . '%',
                'endereco like' => '%' . $pesquisa . '%',
                'responsavel like' => '%' . $pesquisa . '%',
                'telefone like' => '%' . $pesquisa . '%',
                'sms_mes' => $pesquisa,
                'seguimento like' => '%' . $pesquisa . '%'
            );
            $this->set('pesquisar', $pesquisa);
        }
        $this->set('clientes', $this->paginate($conditions));
        $this->set('seguimentos', $this->seguimentos);
        
    }
    
    private function getUltimas4Semanas() {
        $datas  = array();
        $agora  = new DateTime('now');
        $agora->sub(new DateInterval("P3W"));
        
        $datas['semanas'][] = $agora->format('W');
        $datas['meses'][] = $agora->format('m');
        $datas['anos'][] = $agora->format('Y');
        $datas['siglas'][] = '"' . $agora->format('W') . '-' . $this->getSiglaMes($agora->format('m')) . '"';
        
        for($i=0; $i<3; $i++) {
            $agora->add(new DateInterval("P1W"));
            $datas['semanas'][] = $agora->format('W');
            $datas['meses'][] = $agora->format('m');
            $datas['anos'][] = $agora->format('Y');
            $datas['siglas'][] = '"' . $agora->format('W') . '-' . $this->getSiglaMes($agora->format('m')) . '"';
        }                
        return $datas;
    }
    
    private function getSiglaMes($mes) {
        switch ($mes) {
                case "01": return 'Jan';
                case "02": return 'Fev';
                case "03": return 'Mar';
                case "04": return 'Abr';
                case "05": return 'Mai';
                case "06": return 'Jun';
                case "07": return 'Jul';
                case "08": return 'Ago';
                case "09": return 'Set';
                case "10": return 'Out';
                case "11": return 'Nov';
                case "12": return 'Dez';
         }
    }
    
    private function carregaGraficoSmsPorSemana() {

        $ultimas4Semanas  = $this->getUltimas4Semanas();
        
        $agora  = new DateTime('now');
        $agora->sub(new DateInterval("P3W"));
        while($agora->format('N') > 1) {
            $agora->sub(new DateInterval("P1D"));
        }

        $clientesComSms = $this->Cliente->find('all', array(
                                    'fields' => array('distinct(Cliente.id)', 'Cliente.nome'),
                                    'joins'=>array(
                                        array(
                                            'table' => 'retornos',
                                            'alias' => 'Retorno',
                                            'conditions' => array(
                                                'Retorno.cliente_id = Cliente.id',
                                                "(Retorno.data BETWEEN '" . $agora->format('Y-m-d') . "' and now())"
                                            )
                                        )
                                    )
                                ));
        
        $this->loadModel('Retorno');
        foreach ($clientesComSms  as $keyCliente => $cliente) {
            foreach ($ultimas4Semanas['semanas'] as $key => $semana) {
                
                $quantidade = $this->Retorno->find('count', array(
                                    'conditions' => array(
                                        'WEEK(Retorno.data)'=>$semana-1,
                                        'Retorno.cliente_id'=>$cliente['Cliente']['id'],
                                        "(Retorno.data BETWEEN '" . $agora->format('Y-m-d') . "' and now())"
                                    )
                                ));
                
                $clientesComSms[$keyCliente]['Cliente']['dados'][$semana] = $quantidade;
            }
        }
        
        $this->set(compact('ultimas4Semanas'));
        $this->set(compact('clientesComSms'));
    }
    
    private function carregaGraficoTotalSmsMes() {
        $this->loadModel('Mensagem');
        $findMensagem   = $this->Mensagem->find('all', array(
                  'fields' => array('sum(total) AS ctotal'), 
                  'conditions'=>array("DATE_FORMAT(envio, '%m%Y')"=>date('mY') )
                ));
        
        $findCliente    = $this->Cliente->find('all', array(
                  'fields' => array('sum(sms_mes) AS ctotal')
                ));
        
        $this->set('totalSmsMesAtual', $findMensagem[0][0]['ctotal']);
        $this->set('totalSmsVendido', $findCliente[0][0]['ctotal']);
    }

    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Cliente']['id'])) {
            if ($this->Cliente->delete($this->data['Cliente']['id'])) {
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
    
    public function isAuthorized($user) {
        if (parent::isAuthorized($user)) {
            if ($user['role'] === 'admin') {
                return true;
            }
        }
        return false;
    }
    
}

?>