<b>Passos para Importação:</b><br><br>
Crie um arquivo texto com as colunas listadas abaixo separadas por ";". <br>
Esse procedimento poder ser feito também criando uma planilha no Excel e depois exportando para o formato CSV.<br><br>
      <pre>
nome;sobrenome;telefone;sexo;email;aniversário;possui filhos
Marcos;Nascimento;(61)9674-8754;M;marcos@hotmail.com;01/02/1974;
Ana Maria;Braga;(61)9873-8734;Feminino;anamaria@hotmail.com;10/05/1980;não
Lucia;;(61)9993-8734;;;15/04/1980;N
Brenda;Golçalvez;(61)6734-8763;F;brenda@gmail.com;10/05/1981;S
...</pre><br>
<?php echo $this->Form->create('Contato', array('type' => 'file', 'url' => "/contatos/importar"));?>
      <div class="col-xs-6">
        <?php
            echo $this->Form->input('conteudo', array('type' => 'file', 'label' => 'Selecione um Arquivo TEXTO ou CSV com o seu conteúdo separado por ";"') );
            echo $this->Form->input('Grupo', array('type' => 'select', 'label' => 'Grupos', 'class'=>'form-control chosen-select', 'multiple'=>true, 'escape'=>false, 'options' => $this->Funcoes->selectThreaded($grupos, 'Grupo') ) );
        ?>
            <div class="no-margin-top">
                <button class="btn btn-sm btn-primary"><i class="ace-icon fa fa-check"></i>Importar</button>
            </div>
      </div>
<?php echo $this->Form->end();?>

<?php echo $this->Html->scriptStart() ?>
      
    $(".chosen-select").chosen();

    $('#ContatoConteudo').ace_file_input({
            no_file:'Nenhum arquivo selecionado ...',
            btn_choose:'Selecionar',
            btn_change:'Alterar',
            droppable:false,
            onchange:null,
            thumbnail:false //| true | large
            //whitelist:'gif|png|jpg|jpeg'
            //blacklist:'exe|php'
            //onchange:''
            //
    });
        
<?php echo $this->Html->scriptEnd() ?>