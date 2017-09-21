<?php

class AniversariantesController extends AppController {
    
    
    public function index() {
        $this->loadModel('Template');
        $this->loadModel('Categoria');
        
        if ( !$this->request->is('post') ) {
            
            if($this->data['Template']['id'] == '') { // CRIA CATEGORIA PARA ESSAS MENSAGENS
                $this->Categoria->create();
                $this->Categoria->save(array('cliente_id'=>$this->Auth->user('cliente_id'), 'nome'=>'ANIVERSÁRIOS', 'tipo'=>'niver'));
            }
            
            $this->Template->create();
            if ($this->Template->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
        
        if (empty($this->data)) {
            $templateNiver  = $this->Template->find('first', array('conditions' =>array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo'=>'niver' )));
            if(empty($templateNiver)) {
                $templateNiver['Template']  = array('cliente_id'=>$this->Auth->user('cliente_id'), 'tipo'=>'niver', 
                                                    'mensagem'=>'{nome}, CONFIGURE AQUI A SUA MENSAGEM DE ANIVERSARIO.');
            }
            $this->data = $templateNiver;
        }
        
    }
    
}

?>