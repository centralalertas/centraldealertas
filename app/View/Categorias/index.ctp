<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Assunto Vinculado',
                        'dominio' => 'Categoria',
                        'coluna' => 'parent_id',
                        'tipo' => 'hidden_select',
                        'data' => $this->Funcoes->selectThreaded($list_categorias, 'Categoria')
                    ),
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Categoria',
                        'coluna' => 'cliente_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('cliente_id')
                    ),
                    array(
                        'nome' => 'Nome',
                        'dominio' => 'Categoria',
                        'coluna' => 'nome',
                        'tipo' => 'text'
                    )
                );
                echo $this->Tabela->imprimir($colunas, $categorias, 'Categoria', array('validationErrors'=>$this->validationErrors, 'threaded'=>true) );
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>true));
?>