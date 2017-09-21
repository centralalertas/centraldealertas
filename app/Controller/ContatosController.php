<?php

class ContatosController extends AppController {
    
    public function index() {
        
        $this->Contato->recursive = 2;
        $this->paginate = array(
            'limit' => 20
        );
        $conditions = array( 'cliente_id' => $this->Auth->user('cliente_id'));
        
        if(AuthComponent::user('role') == 'subordinado') {
            $conditions = array( 'usuario_id' => $this->Auth->user('id'));
        }
        
        if ($this->request->is('post') && isset($this->data['Contato']['id'])) {
            //echo "<pre>";print_r($_POST);echo "</pre>";exit();
            $this->Contato->create();
            $this->Contato->validator()->remove('Grupo');
            //echo "teste";
            //echo "<pre>";print_r($this->request->data);echo "</pre>";exit();
            if ($this->Contato->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
                $this->redirect('/contatos');
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
                $this->redirect('/contatos');
            }
        }
                
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['or'] = array(
                'nome like' => '%' . $pesquisa . '%',
                'sobrenome like' => '%' . $pesquisa . '%',
                'telefone like' => '%' . $pesquisa . '%',
                'email like' => '%' . $pesquisa . '%'
            );
            $this->set('pesquisar', $pesquisa);
        }
        $this->set('contatos', $this->paginate($conditions));
        
        $this->set('grupos', $this->Contato->Grupo->find('threaded', array(
            'conditions' => array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null' ),
            'order' => 'nome ASC'
        )));
    }
    
    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Contato']['id'])) {
            if ($this->Contato->delete($this->data['Contato']['id'])) {
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
    
    public function importar() {
        $this->set('grupos', $this->Contato->Grupo->find('threaded', array(
            'conditions' => array( 'cliente_id' => $this->Auth->user('cliente_id') ),
            'order' => 'nome ASC'
        )));
        
        if($this->request->is('post')) {
            
            set_time_limit(0);
            
            if( isset( $this->data['Contato']['conteudo'] ) && isset( $this->data['Grupo']['Grupo'][0] ) ) {
            
                $file   = fopen($this->data['Contato']['conteudo']['tmp_name'], 'r');
                $importados = 0;
                while (($data = fgetcsv($file, 0, ";")) !== FALSE) {
                    if($data[0] != 'nome') {
                        $contato = array();
                        $find    = $this->Contato->find('first', array('conditions' => array( 'telefone' => $data[2], 'cliente_id' => $this->Auth->user('cliente_id')) ));
                        if(isset($find['Contato']['id']) && $find['Contato']['id'] > 0 ) {
                            $contato['Contato']['id'] = $find['Contato']['id'];
                        }

                        $contato['Contato']['cliente_id'] = $this->Auth->user('cliente_id');
                        $contato['Contato']['nome'] = utf8_encode($data[0]);
                        $contato['Contato']['sobrenome'] = utf8_encode($data[1]);
                        $contato['Contato']['telefone'] = $data[2];
                        if(isset( $data[3] )) {
                            $contato['Contato']['sexo'] = $this->getSexo($data[3]);
                        }
                        if(isset( $data[4] )) {
                            $contato['Contato']['email'] = $data[4];
                        }
                        if(isset( $data[5] )) {
                            $contato['Contato']['aniversario'] = $data[5];
                        }
                        if(isset( $data[6] )) {
                            $contato['Contato']['filho'] = $this->getSimNao(utf8_encode($data[6]));
                        }

                        $contato['Grupo'] = $this->data['Grupo'];

                        $this->Contato->create();
                        $this->Contato->validator()->remove('Grupo');
                        if($this->Contato->save($contato)) {
                            $importados++;
                        }
                    }
                }

                $this->Session->setFlash('Foram importados ' . $importados . ' registros.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível importar. Verifique se selecionou um arquivo e escolheu ao menos um Grupo.');
            }
        }
    }
    
    private function getSimNao($valor) {
        $valor  = mb_strtoupper($valor, 'UTF-8');
        switch ($valor) {
            case 'SIM' :
            case 'S' :
                return 'S';
            case 'NÃO' :
            case 'NAO' :
            case 'N' :
                return 'N';
            default :
                return '';
        }
    }
    
    private function getSexo($valor) {
        $valor  = mb_strtoupper($valor, 'UTF-8');
        switch ($valor) {
            case 'MASCULINO' :
            case 'M' :
                return 'M';
            case 'FEMININO' :
            case 'F' :
                return 'F';
            default :
                return '';
        }
    }
    
    public function getContato($telefone) {
        $celular    = preg_replace("/[^0-9]/", "", $telefone);
        echo json_encode( $this->Contato->find('first', array(
            'conditions'=>array( 'trim(replace(replace(replace(replace(telefone, "(", ""), ")", ""), " ", ""), "-", ""))'=>$celular, 'cliente_id' => $this->Auth->user('cliente_id'))) ));
        exit();
    }
    
}

?>