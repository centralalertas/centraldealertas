<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Grupo Vinculado',
                        'dominio' => 'Grupo',
                        'coluna' => 'parent_id',
                        'tipo' => 'hidden_select',
                        'data' => $this->Funcoes->selectThreaded($list_grupos, 'Grupo')
                    ),
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Grupo',
                        'coluna' => 'cliente_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('cliente_id')
                    ), 
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Grupo',
                        'coluna' => 'usuario_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('id')
                    ),
                    array(
                        'nome' => 'Cor',
                        'dominio' => 'Grupo',
                        'coluna' => 'cor',
                        'tipo' => 'hidden_cor',
                        'data' => $cores
                    ),
                    array(
                        'nome' => 'Nome',
                        'dominio' => 'Grupo',
                        'coluna' => 'nome',
                        'tipo' => 'text',
                        'concatenar' => array('coluna'=>'cor', 'tipo'=>'cor')
                    ),
                    array(
                        'nome' => 'Compartilhado',
                        'nomeForm' => 'Compartilhar',
                        'dominio' => 'Grupo',
                        'coluna' => 'visivel',
                        'tipo' => 'checkbox'
                    ),
                    array(
                        'nome' => 'Qtd Contatos',
                        'dominio' => 'Grupo',
                        'coluna' => 'contatos',
                        'tipo' => 'text',
                        'input' => false
                    )
                );
                echo $this->Tabela->imprimir($colunas, $grupos, 'Grupo', array(
                    'validationErrors'=>$this->validationErrors, 
                    'threaded'=>true, 
                    'funcaoValidaExclusao'=>'validaExclusaoGrupo', 
                    'verificaUsuarioParaAtualizar'=>true,
                    'removeadd' => true
                ) );
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>true));
?>