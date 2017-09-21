<?php

class CategoriasController extends AppController {
    
    
    public function index() {
        
        if ($this->request->is('post') && isset($this->data['Categoria']['id'])) {
            $this->Categoria->create();
            if ($this->Categoria->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
        
        $conditions = array( 'cliente_id' => $this->Auth->user('cliente_id'), 'tipo is null' );
        
        $list_categorias    = $this->Categoria->find('threaded', array(
            'conditions' => $conditions,
            'order' => 'nome ASC'
        ));
        $this->set('list_categorias', $list_categorias);
        
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['nome like'] = '%' . $pesquisa . '%';
            $this->set('pesquisar', $pesquisa);
            
            $this->set('categorias', $this->Categoria->find('threaded', array(
                'conditions' => $conditions,
                'order' => 'nome ASC'
            )));
        } else {
            $this->set('categorias', $list_categorias);
        }
    }
    
    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Categoria']['id'])) {
            if ($this->Categoria->delete($this->data['Categoria']['id'])) {
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
    
}

?>