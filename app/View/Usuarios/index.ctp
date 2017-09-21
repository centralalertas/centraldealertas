<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Usuario',
                        'coluna' => 'cliente_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('cliente_id')
                    ),
                    array(
                        'nome' => 'Nome',
                        'dominio' => 'Usuario',
                        'coluna' => 'nome',
                        'tipo' => 'text'
                    ),
                    array(
                        'nome' => 'Usuário/Email',
                        'dominio' => 'Usuario',
                        'coluna' => 'username',
                        'tipo' => 'email',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Senha',
                        'dominio' => 'Usuario',
                        'coluna' => 'password',
                        'tipo' => 'password'
                    ),
                    array(
                        'nome' => 'Perfil',
                        'dominio' => 'Usuario',
                        'coluna' => 'role',
                        'tipo' => 'select',
                        'data' => $perfis
                    )
                );
                
                if(AuthComponent::user('role') == 'admin') {
                    $colunas[0] = array(
                        'nome' => 'Cliente',
                        'dominio' => 'Usuario',
                        'coluna' => 'cliente_id',
                        'tipo' => 'select',
                        'select' => array('dominio' => 'Cliente', 'coluna' => 'nome'),
                        'data' => $clientes
                    );
                }
                
                echo $this->Tabela->imprimir($colunas, $usuarios, 'Usuario', array('validationErrors'=>$this->validationErrors) );
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>false));
?>