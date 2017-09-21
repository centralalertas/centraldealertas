<?php

class GruposController extends AppController {
    
    var $arrayThreaded   = array();

    public $cores = array(
        '#ac725e' => '#ac725e',
        '#d06b64' => '#d06b64',
        '#f83a22' => '#f83a22',
        '#fa573c' => '#fa573c',
        '#ff7537' => '#ff7537',
        '#ffad46' => '#ffad46',
        '#42d692' => '#42d692',
        '#16a765' => '#16a765',
        '#7bd148' => '#7bd148',
        '#b3dc6c' => '#b3dc6c',
        '#fbe983' => '#fbe983',
        '#fad165' => '#fad165',
        '#92e1c0' => '#92e1c0',
        '#9fe1e7' => '#9fe1e7',
        '#9fc6e7' => '#9fc6e7',
        '#4986e7' => '#4986e7',
        '#9a9cff' => '#9a9cff',
        '#b99aff' => '#b99aff',
        '#c2c2c2' => '#c2c2c2',
        '#cabdbf' => '#cabdbf',
        '#cca6ac' => '#cca6ac',
        '#f691b2' => '#f691b2',
        '#cd74e6' => '#cd74e6',
        '#a47ae2' => '#a47ae2',
        '#555' => '#555'
    ); 

     function selectThreaded($registros, $dominio) {
        
        $this->arrayThreaded = array();
        foreach ($registros as $registro):
            $this->setArrayThreaded($registro, $dominio);
        endforeach;

        //echo "<pre>";print_r($this->arrayThreaded);echo "</pre>";exit();
        
        return $this->arrayThreaded;
        
    }
    
    private function setArrayThreaded($registro, $dominio, $nivel = 0) {
            
        $espacos    = $this->getEspacosPorNivel($nivel);
        $this->arrayThreaded[ $registro[$dominio]['id'] ] = $espacos . $registro[$dominio]['nome'];

        foreach ($registro['children'] as $filho):
            $novo_nivel = $nivel + 1;
            $this->setArrayThreaded($filho, $dominio, $novo_nivel);
        endforeach;
        
    }


    private function getEspacosPorNivel($nivel)
    {
        if ($nivel == 0) {
            return "";
        } else {
            $nivel = $nivel * 3;
            $espacos = '';
            for ($i = 0; $i < $nivel; $i ++) {
                $espacos .= "&nbsp; ";
            }
            return $espacos;
        }
    }

    public function changer() {
        //echo "<pre>";print_r($this->request['data']);echo "</pre>";exit();

        /*$data=$this->Grupo->query("
            select * from contatos as c 
            inner join contatos_grupos as cg on cg.contato_id = c.id
            where cg.contato_id = 3730 and cg.grupo_id = 696;
        ");*/
        $delete = $this->Grupo->query("
            delete from contatos_grupos where contato_id = {$this->request['data']['code_contato']} and grupo_id = {$this->request['data']['code_grupo']};
        "); 
        //echo "<pre>";print_r($delete);echo "</pre>";exit();
        $insert = $this->Grupo->query("
            insert contatos_grupos value({$this->request['data']['code_contato']},{$this->request['data']['move']});
        ");
        //echo "<pre>";print_r($data);echo "</pre>";
        
        $this->Session->setFlash('Contato removido deste grupo com sucesso.', 'default', array('class'=>'message success'));
        $this->redirect('/grupos/removeadd/' . $this->request['data']['code_grupo']);
    }

    public function removeadd($id){
         $query =  $this->Grupo->query('
                    select * from grupos as g 
                    inner join contatos_grupos as cg on cg.grupo_id = g.id
                    inner join contatos as c on c.id = cg.contato_id
                    where g.id = '.$id.';');
         //echo "<pre>";print_r($query);echo "</pre>";

        $conditions = array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null');
        
        if ( $this->Auth->user('role') === 'subordinado' ) {
            $conditions['or']   = array('usuario_id'=>$this->Auth->user('id'), 'visivel'=>'1');
        }
        
        $list_grupos    = $this->Grupo->find('threaded', array(
            'conditions' => $conditions,
            'order' => 'nome ASC'
        ));
        $this->set('query', $query);
        $this->set('options', $this->selectThreaded($list_grupos, 'Grupo'));
        //echo "<pre>";print_r($this->selectThreaded($list_grupos, 'Grupo'));echo "</pre>";
    }
    
    public function index() {
        
        if ($this->request->is('post') && isset($this->data['Grupo']['id'])) {
            $this->Grupo->create();
            if ($this->Grupo->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
        
        $conditions = array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null');
        
        if ( $this->Auth->user('role') === 'subordinado' ) {
            $conditions['or']   = array('usuario_id'=>$this->Auth->user('id'), 'visivel'=>'1');
        }
        
        $list_grupos    = $this->Grupo->find('threaded', array(
            'conditions' => $conditions,
            'order' => 'nome ASC'
        ));
        $this->set('list_grupos', $list_grupos);
        
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['nome like'] = '%' . $pesquisa . '%';
            $this->set('pesquisar', $pesquisa);
            
            $this->set('grupos', $this->Grupo->find('threaded', array(
                'conditions' => $conditions,
                'order' => 'nome ASC'
            )));
        } else {
            $this->set('grupos', $list_grupos);
        }
        
        $this->set('cores', $this->cores);
    }
    
    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Grupo']['id'])) {
            if ($this->Grupo->delete($this->data['Grupo']['id'])) {
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
    
    public function countcontatos() {
        $count  = 0;
        if (isset($this->data['grupos'])) {
            $quantidade =  $this->Grupo->query('
                    SELECT count( distinct ContratoGrupo.contato_id) quantidade
                        FROM contatos_grupos as ContratoGrupo 
                    where ContratoGrupo.grupo_id in(' . $this->data['grupos'] . ')');
            $count  = $quantidade[0][0]['quantidade'];
        }
        echo $count;
        exit ();
    }
}

?>