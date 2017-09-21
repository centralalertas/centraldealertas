<!-- <pre>
     PREZADO CLIENTE, ESTAMOS REALIZANDO MELHORIAS NO AGENDAMENTO DE ENVIO DE MENSAGENS. POR ESSE MOTIVO, DESABILITAMOS ESSA FUNCIONALIDADE 
     TEMPORARIAMENTE, ENQUANTO ISSO, O DISPARO EST&Aacute; FUNCIONANDO NORMALMENTE. 
     OBRIGADO A TODOS. 
</pre> -->
<br/>
<br/>
<?php
    echo $this->Form->create('Template', array('inputDefaults' => array('class'=>'form-control')) );
    echo $this->Form->hidden('id');
    echo $this->Form->hidden('cliente_id');
    echo $this->Form->hidden('tipo');
    echo $this->Form->input('mensagem', array('label'=>'Texto a ser usado nas Mensagens de Aniversário', 'type'=>'textarea', 'class'=>'txtsms', 'maxlength' => '300'));
?>

    <button type="submit" class="width-20 pull-right btn btn-sm btn-primary">
            <span class="bigger-110">Salvar</span>
    </button>

<?php
    echo $this->Form->end();
?>

<script type="text/javascript">
    jQuery(function($) {

        $(".txtsms").smsHelper({menor: true, infoText: 'Limite para uma mensagem: ', limit: false, chunks: 0});

    })
    
</script>