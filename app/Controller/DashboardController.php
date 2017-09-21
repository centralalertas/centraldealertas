<?php
App::uses('HttpSocket', 'Network/Http');

class DashboardController extends AppController {
    
    public function index() {
        
        // SMS POR MÊS
		$this->loadModel('Cliente');
        $this->loadModel('Grupo');
        $this->loadModel('SmsPorGrupo');
		$this->loadModel('Cliente');
		$this->loadModel('Retorno');
        $ultimos6Meses  = $this->getUltimos6Meses();		
        
        $condicaoSmsPorMes  = array( 'Grupo.cliente_id' => $this->Auth->user('cliente_id') );
        if(AuthComponent::user('role') == 'subordinado') {
            $condicaoSmsPorMes['or']  = array( 'Grupo.usuario_id' => $this->Auth->user('id'), 'Grupo.visivel' => 1 );
        }

		// Envia SMS para Administrador de qual cliente esta ativo
        if(AuthComponent::user('role') == 'admin') {
            $this->Auth->user('cliente_id');
        }	
		
        $gruposSmsPorMes = $this->Grupo->find('all', array('conditions'=>$condicaoSmsPorMes) );
        
        foreach ($gruposSmsPorMes  as $keyGrupo => $grupo) {
            foreach ($ultimos6Meses['meses'] as $key => $mes) {
                $smsPorGrupo = $this->SmsPorGrupo->find('first', array(
                                    'conditions'=>array('grupo_id'=>$grupo['Grupo']['id'], 'mes'=>$mes, 'ano'=>$ultimos6Meses['anos'][$key])) );
                if(isset($smsPorGrupo['SmsPorGrupo'])) {
                    $gruposSmsPorMes[$keyGrupo]['Grupo']['dados'][] = $smsPorGrupo['SmsPorGrupo']['quantidade'];
                } else {
                    $gruposSmsPorMes[$keyGrupo]['Grupo']['dados'][] = 0;
                }
            }
        }
        
        $this->set(compact('ultimos6Meses'));
        $this->set(compact('gruposSmsPorMes'));
        
        // CONTATOS POR GRUPO
        $this->loadModel('ContatoPorGrupo');
        
        $condicaoContatosPorGrupo = array( 'ContatoPorGrupo.cliente_id' => $this->Auth->user('cliente_id') );
        if(AuthComponent::user('role') == 'subordinado') {
            $condicaoContatosPorGrupo[] = 'ContatoPorGrupo.id in( SELECT DISTINCT id from grupos where cliente_id = ' . $this->Auth->user('cliente_id') . ' and (usuario_id = ' . $this->Auth->user('id') . ' or visivel = 1) )';
        }
        
        $contatosPorGrupo   = $this->ContatoPorGrupo->find('all', array(
                              'conditions'=>$condicaoContatosPorGrupo
            ));
        $this->set(compact('contatosPorGrupo'));
        
        if(AuthComponent::user('role') != 'subordinado') {
        // MENSAGENS POR CATEGORIA
        $this->loadModel('MensagemPorCategoria');
        $mensagensPorCategoria  = $this->MensagemPorCategoria->find('all', array(
                                    'conditions'=>array( 'MensagemPorCategoria.cliente_id'=>$this->Auth->user('cliente_id') )
            ));
        $this->set(compact('mensagensPorCategoria'));
        }
        
        // ENVIOS NO MÊS
        $this->loadModel('Mensagem');
        
        $condicaoEnvioPorMes = array('Mensagem.cliente_id'=>$this->Auth->user('cliente_id'), "DATE_FORMAT(envio, '%m%Y')"=>date('mY') );
        if(AuthComponent::user('role') == 'subordinado') {
            $condicaoEnvioPorMes['Mensagem.usuario_id'] = $this->Auth->user('id');
        }
        
        $findMensagem   = $this->Mensagem->find('all', array(
                  'fields' => array('sum(total) AS ctotal'), 
                  'conditions' => $condicaoEnvioPorMes
                ));
        $this->set('totalSmsMesAtual', $findMensagem[0][0]['ctotal']);
    }
    
    private function getUltimos6Meses() {
        $datas  = array();
        $agora  = new DateTime('now');
        $agora->sub(new DateInterval("P5M"));
        
        $datas['meses'][] = $agora->format('m');
        $datas['anos'][] = $agora->format('Y');
        $datas['siglas'][] = $this->getSiglaMes($agora->format('m'));
        
        for($i=0; $i<5; $i++) {
            $agora->add(new DateInterval("P1M"));
            $datas['meses'][] = $agora->format('m');
            $datas['anos'][] = $agora->format('Y');
            $datas['siglas'][] = $this->getSiglaMes($agora->format('m'));
        }                
        return $datas;
    }
    
    private function getSiglaMes($mes) {
        switch ($mes) {
                case "01": return '"Jan"';
                case "02": return '"Fev"';
                case "03": return '"Mar"';
                case "04": return '"Abr"';
                case "05": return '"Mai"';
                case "06": return '"Jun"';
                case "07": return '"Jul"';
                case "08": return '"Ago"';
                case "09": return '"Set"';
                case "10": return '"Out"';
                case "11": return '"Nov"';
                case "12": return '"Dez"';
         }
    }
}

?>