<!--<pre>
     PREZADO CLIENTE, ESTAMOS REALIZANDO MELHORIAS NO AGENDAMENTO DE ENVIO DE MENSAGENS. POR ESSE MOTIVO, DESABILITAMOS ESSA FUNCIONALIDADE 
     TEMPORARIAMENTE, ENQUANTO ISSO, O DISPARO EST&Aacute; FUNCIONANDO NORMALMENTE. 
     OBRIGADO A TODOS. 
</pre>-->
<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Mensagem',
                        'coluna' => 'cliente_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('cliente_id')
                    ),
                    array(
                        'nome' => 'Cliente',
                        'dominio' => 'Mensagem',
                        'coluna' => 'usuario_id',
                        'tipo' => 'hidden',
                        'data' => AuthComponent::user('id')
                    ),
                    array(
                        'nome' => 'Total',
                        'dominio' => 'Mensagem',
                        'coluna' => 'total',
                        'tipo' => 'hidden',
                        'data' => '0'
                    ),
                    array(
                        'nome' => 'Assunto',
                        'dominio' => 'Mensagem',
                        'coluna' => 'categoria_id',
                        'tipo' => 'select',
                        'select' => array('dominio' => 'Categoria', 'coluna' => 'nome'),
                        'data' => $this->Funcoes->selectThreaded($categorias, 'Categoria')
                    ),
                    array(
                        'nome' => 'Mensagem',
                        'dominio' => 'Mensagem',
                        'coluna' => 'mensagem',
                        'tipo' => 'textarea',
                        'class' => 'width-60',
                        'help' => 'Algumas operadoras possuem bloqueio por palavras. Antes de enviar sua mensagem verifique se não está utilizando palavras como: promoção, brinde, desconto etc.'
                    ),
                    array(
                        'nome' => 'Arquivo',
                        'dominio' => 'Mensagem',
                        'coluna' => 'arquivo',
                        'tipo' => 'file', 
                        'help' => 'Selecione neste item: imagem, video ou audio, etc.'
                    ),
                    array(
                        'nome' => 'checkenbox_disable',
                        'dominio' => 'Mensagem',
                        'coluna' => 'checkenbox_disable',
                        'tipo' => 'checkenbox_disable', 
                        'help' => 'Escolha a rede social que você quer mandar.'
                    ),
                    array(
                        'nome' => 'Datas para o Envio',
                        'dominio' => 'Mensagem',
                        'coluna' => 'envio',
                        'tipo' => 'hidden_datetime', 
                        'help' => 'Para enviar a mesma mensagem várias vezes basta selecionar as Datas desejadas no calendário abaixo.'
                    ),
                    array(
                        'nome' => 'Início do Disparo',
                        'dominio' => 'Mensagem',
                        'coluna' => 'hora',
                        'tipo' => 'time_form', 
                        'help' => 'O horário de envio envio deverá ser de 08:00 ás 20:00 hs.'
                    ),
                    array(
                        'nome' => 'Situação',
                        'dominio' => 'Mensagem',
                        'coluna' => 'envio',
                        'tipo' => 'datetime_now',
                        'data' => array('maior'=>'AGENDADA', 'menor'=>'ENVIADA'),
                        'input' => false
                    ),
                    array(
                        'nome' => 'Grupos',
                        'dominio' => 'Mensagem',
                        'coluna' => 'Grupo',
                        'tipo' => 'multiple_cor',
                        'idCheckbox' => 'CheckboxAllGrupos',
                        'data' => $this->Funcoes->selectThreaded($grupos, 'Grupo')
                    )
                );
                
                echo $this->Tabela->imprimir($colunas, $mensagens, 'Mensagem', array('validationErrors'=>$this->validationErrors, 
                                                                                     'btSalvar'=>'confirmaMensagem', 'habilitarEdicao'=>false,
                                                                                     'funcaoValidaExclusao'=>'validaExclusaoMensagem', 
                                                                                     'nomeBotaoNovo'=>'Novo Envio',"btTextSalvar" => "Enviar") );
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>false));
?>