<?php

class UsuariosController extends AppController {

//    public function beforeFilter() {
//        parent::beforeFilter();
//        $this->Auth->allow('index');
//    }
    
    public $perfis = array(
            'admin'  => 'Administrador',
            'cliente' => 'Gestor',
            'subordinado' => 'Comunicador'
        );
    
    public function index() {
        
        $this->Usuario->recursive = 2;
        $this->paginate = array(
            'limit' => 20
        );
        $conditions = array();
        if ( $this->Auth->user('role') != 'admin' ) {
            $conditions = array( 'cliente_id' => $this->Auth->user('cliente_id'), 'role !='=>'admin' );
        }        
		
        if ($this->request->is('post') && isset($this->data['Usuario']['id'])) {
            $this->Usuario->create();
            if ($this->Usuario->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
                
        if ($this->request->is('post') && isset($this->data['pesquisar']) && $this->data['pesquisar'] != '') {
            $pesquisa   = mb_strtoupper($this->data['pesquisar'], 'UTF-8');
            $conditions['or'] = array(
                ' Usuario.nome like' => '%' . $pesquisa . '%',
                ' Usuario.username like' => '%' . $pesquisa . '%'
            );
            $this->set('pesquisar', $pesquisa);
        }
        $this->set('usuarios', $this->paginate($conditions));
        
        if ( $this->Auth->user('role') === 'admin' ) {
            $this->set('clientes', $this->Usuario->Cliente->find('list', array(
                'order' => 'nome ASC'
            )));
        } else {
            unset($this->perfis['admin']);
        }
        
        $this->set('perfis', $this->perfis);
        
    }
    
    public function delete()
    {
        if ($this->request->is('post') && isset($this->data['Usuario']['id'])) {
            if ($this->Usuario->delete($this->data['Usuario']['id'])) {
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
    
    public function login() {
        $this->layout   = 'login';
        if ($this->Auth->login()) {
            $agora  = new DateTime('now');
            $this->Usuario->id = $this->Auth->user('id');
            $this->Usuario->saveField("last_login", $agora->format('Y-m-d H:i:s'), array('callbacks'=>false));
            $this->redirect($this->Auth->redirect());
        } else {
            if($this->request->is('post')) {
                $this->Flash->error('Usuário ou senha inválido, tente novamente.');
            }
        }
    }
    
    public function sair() {
        $this->redirect($this->Auth->logout());
    }
    
    public function perfil() {
        if (isset($this->data['Usuario']['id'])) {
            $this->Usuario->create();
            if ($this->Usuario->save($this->request->data)) {
                $this->Session->setFlash('Registro salvo com sucesso.', 'default', array('class'=>'message success'));
            } else {
                $this->Session->setFlash('Não foi possível salvar. Tente novamente.');
            }
        }
        
        $this->data = $this->Usuario->read(null, $this->Auth->user('id'));
    }
    
    public function isAuthorized($user) {
        if (parent::isAuthorized($user)) {
            if ($user['role'] === 'admin' || $user['role'] === 'cliente') {
                return true;
            } else {
                if ($this->action === 'perfil' || $this->action === 'sair') {
                    return true;
                }
            }
        }
        if ($this->action === 'login') {
            return true;
        }
        return false;
    }
    
    public function getLogo() {
        echo $this->Auth->user('Cliente.logo');
        exit();
    }
}

?>