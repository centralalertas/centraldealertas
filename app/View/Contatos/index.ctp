<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Contato',
                        'coluna' => 'cliente_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('cliente_id')
                    ),
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Contato',
                        'coluna' => 'usuario_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('id')
                    ),
                    array(
                        'nome' => 'Nome',
                        'dominio' => 'Contato',
                        'coluna' => 'nome',
                        'tipo' => 'text'
                    ),
                    array(
                        'nome' => 'Sobrenome',
                        'dominio' => 'Contato',
                        'coluna' => 'sobrenome',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Celular',
                        'dominio' => 'Contato',
                        'coluna' => 'telefone',
                        'tipo' => 'phone'
                    ),
                    array(
                        'nome' => 'Fone Fixo',
                        'dominio' => 'Contato',
                        'coluna' => 'celular',
                        'tipo' => 'phone'
                    ),
                    array(
                        'nome' => 'UF',
                        'dominio' => 'Contato',
                        'coluna' => 'uf',
                        'tipo' => 'select',
                        'data' => array(
                            'DF'=>'Brasilia'
                        ), 
                        'class' => 'hidden-sm hidden-xs'
                    ),                    
                    array(
                        'nome' => 'Cidade',
                        'dominio' => 'Contato',
                        'coluna' => 'cidade',
                        'tipo' => 'select',
                        'data' => array(
                            0   => "Ceilândia",
                            1   => "Taguatinga",
                            2   => "Samambaia",
                            3   => "Plano Piloto",
                            4   => "Planaltina",
                            5   => "Recanto das Emas",
                            6   => "Águas Claras",
                            7   => "Gama",
                            8   => "Guará",
                            9   => "Santa Maria",
                            10  => "São Sebastião",
                            11  => "Sobradinho",
                            12  => "Sobradinho II",
                            13  => "Vicente Pires",
                            14  => "Cruzeiro",
                            15  => "Riacho Fundo",
                            16  => "Itapoã",
                            17  => "Brazlândia",
                            18  => "Sudoeste/Octogonal",
                            19  => "Paranoá",
                            20  => "SIA",
                            21  => "Riacho Fundo II",
                            22  => "Lago Norte",
                            23  => "Núcleo Bandeirante",
                            24  => "SCIA",
                            25  => "Jardim Botânico",
                            26  => "Park Way",
                            27  => "Lago Sul",
                            28  => "Candangolândia",
                            29  => "Fercal",
                            30  => "Varjão"
                        ), 
                        'class' => 'hidden-sm hidden-xs'
                    ),                    
                    array(
                        'nome' => 'Sexo',
                        'dominio' => 'Contato',
                        'coluna' => 'sexo',
                        'tipo' => 'select',
                        'data' => array('M'=>'Masculino', 'F'=>'Feminino'), 
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Email',
                        'dominio' => 'Contato',
                        'coluna' => 'email',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Aniversário',
                        'dominio' => 'Contato',
                        'coluna' => 'aniversario',
                        'tipo' => 'date',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Possui Filhos?',
                        'dominio' => 'Contato',
                        'coluna' => 'filho',
                        'tipo' => 'select',
                        'data' => array('S'=>'Sim', 'N'=>'Não'),
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Grupos',
                        'dominio' => 'Contato',
                        'coluna' => 'Grupo',
                        'tipo' => 'multiple_cor',
                        'data' => $this->Funcoes->selectThreaded($grupos, 'Grupo')
                    )
                );
                echo $this->Tabela->imprimir($colunas, $contatos, 'Contato', array('validationErrors'=>$this->validationErrors));
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>false));
?>