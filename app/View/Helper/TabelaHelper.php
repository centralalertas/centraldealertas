<?php

class TabelaHelper extends AppHelper {
    
    var $helpers = array('Form', 'JqueryValidation', 'Funcoes');
    
    var $opcoes  = array('validationErrors'=>null, 'threaded'=>false, 'btSalvar'=>null, 'funcaoValidaExclusao'=>'',
                         'habilitarEdicao'=>true, 'habilitarExclusao'=>true, 'verificaUsuarioParaAtualizar'=>false, 'nomeBotaoNovo'=>'Novo',
                         'formOpcoes'=>array('id'=>'form_crud',"enctype"=>"multipart/form-data"));
    
    private function checkError($validationErrors) {
        foreach ($validationErrors as $domino) :
            if(count($domino) > 0) {
                return true;
            }
        endforeach;
        return false;
    }
    
    function imprimir($colunas, $registros, $dominioPrincipal, $opcoes = array()) {
        $this->opcoes   = array_merge($this->opcoes, $opcoes);
        foreach ($this->opcoes as $opcao => $valor) :
            $$opcao = $valor;
        endforeach;
            // echo "<pre>";print_r($opcoes);echo "</pre>";
?>
        <div>
            <div class="nav-search" id="nav-search">
                <?php
                    echo $this->Form->create($dominioPrincipal, array('id'=>'form_pesquisa', 'class'=>'form-search'));
                    $value  = '';
                    if(isset($this->data['pesquisar'])) {
                        $value  = $this->data['pesquisar'];
                    }
                ?>
                    <span class="input-icon">
                        <input value="<?php echo $value; ?>" name="pesquisar" type="text" placeholder="Pesquisar..." class="nav-search-input" id="nav-search-input" autocomplete="off">
                        <i class="ace-icon fa fa-search nav-search-icon"></i>
                    </span>
                <?php
                    echo $this->Form->end();
                ?>
            </div>
            <a id="novo_registro" href="#modal-form" data-toggle="modal" class="btn btn-white btn-sm btn-info btn-bold"><i class="ace-icon fa fa-plus-circle bigger-100"></i> <?php echo $nomeBotaoNovo; ?></a>
        </div>
        <table class="table <?php if($threaded) { echo 'tree';} ?> table-striped table-bordered table-hover" id="list_crud">
                <thead>
                    <tr>
                    <?php
                    $treeColumn = 0;
                    $primeiraColuna = false;
                    foreach ($colunas as $coluna) :
                        $class  = '';
                        // if($coluna['tipo'] == 'hidden_select' || $coluna['tipo'] == 'hidden_datetime' || $coluna['tipo'] == 'hidden' || $coluna['tipo'] == 'hidden_cor' || $coluna['tipo'] == 'password' || $coluna['tipo'] == 'file' || $coluna['tipo'] == 'time_form'){
                        if(in_array($coluna['tipo'],array('hidden_select', 'hidden_datetime', 'hidden', 'hidden_cor', 'password', 'file', 'time_form','checkenbox_disable')) ){
                            $class = 'hidden"';
                            if(!$primeiraColuna) {
                                $treeColumn++;
                            }
                        } else {
                            $primeiraColuna = true;
                        }
                        
                        if ( isset( $coluna['class'] ) ) {
                            $class .= ' ' . $coluna['class'];
                        }
                    ?>
                        <th class="<?php echo $class; ?>"><?php if($coluna['tipo'] != 'file') {echo $coluna['nome'];} ?></th>
                    <?php
                    endforeach;
                    ?>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    foreach ($registros as $registro) :
                        $this->imprimirTr($registro, $dominioPrincipal, $colunas);
                    endforeach;
                    ?>
                </tbody>
        </table>
        
        <div id="modal-form" class="modal fade" tabindex="-1" data-backdrop="static">
                <div class="modal-dialog">
                    <?php
                        echo $this->Form->create($dominioPrincipal, $this->opcoes['formOpcoes']);
                    ?>
                        <div class="modal-content">
                                <div class="modal-header no-padding">
                                        <div class="table-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                        <span class="white">&times;</span>
                                                </button>
                                            <span id="titulo_form"><?php echo $nomeBotaoNovo; ?></span>
                                        </div>
                                </div>

                                <div class="modal-body no-padding">
                                    <div class="col-xs-12" style="background-color: #FFF;">
                                        <?php
                                        echo $this->Form->hidden("id");
                                        foreach ($colunas as $coluna) :
                                            $opcoes = array('label' => ' ' . $coluna['nome'] . ' ', 'class' => 'form-control');
                                            if( isset( $coluna['help'] ) ) {
                                                $opcoes['label']  .= '<span class="help-button tooltip-info" data-rel="tooltip" data-original-title="' . $coluna['help'] . '">?</span> ';
                                            }
                                        
                                            switch ($coluna['tipo']) {
                                                case 'hidden_select':
                                                case 'select':
                                                    $opcoes['class']  = 'form-control chosen-select';
                                                    $opcoes['options']  = $coluna['data'];
                                                    $opcoes['escape']   = false;
                                                    $opcoes['empty']    = 'Selecione...';
                                                    break;
                                                case 'multiple_cor':
                                                    if( isset($coluna['data']) && is_array($coluna['data']) && isset($coluna['idCheckbox']) ) { ?>
                                                        <div class="checkbox" style="clear:both">
                                                            <label>
                                                                <input id="<?php echo $coluna['idCheckbox']; ?>" class="ace ace-checkbox-2" type="checkbox" />
                                                                <span class="lbl"> &nbsp; Selecionar Todos os <?php echo $coluna['nome']; ?></span>
                                                            </label>
                                                        </div>
                                                    <?php
                                                    }
                                                    $opcoes['class']  = 'form-control chosen-select';
                                                    $opcoes['multiple']  = true;
                                                    $opcoes['options']  = $coluna['data'];
                                                    $opcoes['escape']   = false;
                                                    break;
                                                case 'hidden_cor':
                                                    $opcoes['class']  = 'hide cores';
                                                    $opcoes['options']  = $coluna['data'];
                                                    break;
                                                case 'phone':
                                                    $opcoes['class']  = 'form-control masktelefone';
                                                    break;
                                                case 'date':
                                                    $opcoes['type']  = 'text';
                                                    $opcoes['class']  = 'form-control date-picker';
                                                    break;
                                                case 'datetime':
                                                case 'hidden_datetime':
                                                    $opcoes['type']  = 'text col-md-6';
                                                    $opcoes['class']  = 'form-control datetime-picker';
                                                    break;
                                                case 'time_form':
                                                    $opcoes['type']  = 'text col-md-6';
                                                    $opcoes['class']  = 'form-control time-picker';
                                                    break;
                                                case 'textarea':
                                                    $opcoes['type']  = 'textarea';
                                                    $opcoes['class']  = 'form-control txtsms';
                                                    $opcoes['rows']   = '3';
                                                    $opcoes['maxlength'] = 500;
                                                    break;
                                                case 'file':
                                                    $opcoes['type']  = 'file';
                                                    $opcoes['class']  = 'form-control fileupload';
                                                    break;
                                            }
                                            
                                            if(isset($coluna['input']) && !$coluna['input']) {
                                                // SEM INPUT NO FORMULÁRIO
                                            }
                                            elseif($coluna['tipo'] == 'hidden') {
                                                echo $this->Form->hidden($coluna['coluna'], array('value'=>$coluna['data']));
                                            }
                                            elseif($coluna['tipo'] == 'checkbox') { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <input name="<?php echo 'data[' . $coluna['dominio'] . '][' . $coluna['coluna'] . ']'; ?>" class="ace ace-checkbox-2" type="checkbox" />
                                                        <span class="lbl"> <?php echo $coluna['nomeForm']; ?></span>
                                                    </label>
                                                </div><?php
                                            }
                                            elseif($coluna['tipo'] == 'checkenbox_disable') { ?>
                                                <div class="checkbox" style="padding: 18px;">                                                         
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">What zap   </span>
                                                        
                                                    </label>
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">facebook - página/perfil  </span>
                                                        
                                                    </label>
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">facebook - Inbox  </span>
                                                        
                                                    </label>
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">Instagran  </span>
                                                        
                                                    </label>
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">Telegran </span>
                                                        
                                                    </label>
                                                    <label>
                                                        <input  class="ace ace-checkbox-2" type="checkbox" />
                                                         <span class="lbl">Twitter</span>
                                                        
                                                    </label>
                                                </div><?php
                                            }
                                            else {
                                                if(!is_array($coluna['coluna'])) {
                                                    echo $this->JqueryValidation->input($coluna['coluna'], $opcoes);
                                                }
                                            }
                                        endforeach;
                                        ?>
                                        <br>
                                    </div>
                                </div>
                                
                                <div class="modal-footer no-margin-top" style="background-color: #FFF;">
                                    <button <?php if($btSalvar!=''){echo 'type="button"';}; ?> class="btn btn-sm btn-primary <?php echo $btSalvar; ?>"><i class="ace-icon fa fa-check"></i><?= isset($btTextSalvar) ? $btTextSalvar : "Salvar"?></button>
                                </div>
                        </div><!-- /.modal-content -->
                    <?php
                        echo $this->Form->end();
                    ?>
                </div><!-- /.modal-dialog -->
        </div>


<script type="text/javascript">
    jQuery(function($) {
        
        function zerarFormulario() {
            var validator = $( "#form_crud" ).validate();
            validator.resetForm();
            $('#form_crud').trigger("reset");
            
            $(":text").each(function () {
                $(this).val("");
            });
            
            $(".form-control").each(function () {
                $(this).val("");
            });

            $(":radio").each(function () {
                $(this).prop({ checked: false })
            });

            $("select").each(function () {
                $(this).val("");
                //$(this).attr('selectedIndex','-1').children("option:selected").removeAttr("selected");
                //$(this).each(function(){ $(this).removeAttr("selected");});

            });
        }
        
        <?php
        if( $this->checkError($validationErrors) ) { ?>
            $('#modal-form').modal('show'); <?php
        }
        ?>
        
        $('.cores').ace_colorpicker();
        
        $('#modal-form').on('shown.bs.modal', function () {
            $('.chosen-select', this).chosen();
        });
        console.log($('.masktelefone'));
        $('.masktelefone').each(function(index, el) {
            if(el.id.indexOf('Celular') != -1)
                $(el).mask('(00) 0000-0000');
            else 
                $(el).mask('(00) 00000-0000');
        });
        
        //$('.date-picker').mask('99/99/9999');
        
        //$('.date-picker').datepicker({
        //        autoclose: true,
        //        todayHighlight: true
                //startDate: '0'
        //});
        
        //$.datetimepicker.setLocale('pt-BR');
//        $('.datetime-picker').datetimepicker({
//            mask:true,
//            format:'d/m/Y H:i',
//            minDate: 0
//        });



//        $('.time-picker').timepicker({
//                minuteStep: 1,
//                showSeconds: false,
//                showMeridian: false,
//                template: 'modal',
//                modalBackdrop: false
//        }).next().on(ace.click_event, function(){
//                $(this).prev().focus();
//        });

        var nowDate = new Date();
        var today = new Date(nowDate.getFullYear(), nowDate.getMonth(), nowDate.getDate(), 0, 0, 0, 0);
        
        $(".time-picker").datetimepicker({
            datepicker:false,
            format:'H:i',
            value: nowDate.getHours() + ':' + nowDate.getMinutes(),
            mask:true,
            step:10,
            minTime:'08:00',
            maxTime:'20:10'
        });
        
        $('#MensagemEnvio').datepicker({
            multidate: true,
            format:'dd/mm/yyyy',
            startDate: today,
            todayHighlight: true
        });
        
        $('.date-picker').datepicker({
            mask:true,
            format:'dd/mm/yyyy'
        });
        
        $('.fileupload').ace_file_input({
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
        
        $(".txtsms").smsHelper({infoText: 'Limite para uma mensagem: ', limit: false, chunks: 1});
        
        $("#novo_registro").bind('click', function(e) {
            $("#flashMessage").hide();
            $("#titulo_form").html("<?php echo mb_strtoupper($nomeBotaoNovo, 'UTF-8'); ?>");
            $("#<?php echo $dominioPrincipal; ?>Id").val('');
            
            zerarFormulario();
            
            $(".chosen-select").trigger("chosen:updated");
            $('.cores').ace_colorpicker('pick', 0);
            
        });
        
        $("#list_crud tbody tr td div a.editar_registro").bind('click', function(e) {
            $("#titulo_form").html("ALTERAR");
            $("#<?php echo $dominioPrincipal; ?>Id").val($(this).attr('val_id'));
            
            zerarFormulario();
            
            <?php
            foreach ($colunas as $coluna) :
                
                switch ($coluna['tipo']) {
                    case 'hidden_cor': ?>
                        $('.cores').ace_colorpicker('pick', $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").text() )); <?php
                    case 'hidden_select': ?>
                        $("select[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]'] option[value='" + $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").text() ) + "']").attr('selected','selected'); <?php
                        break;
                    case 'select': ?>
                        $("select[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]'] option[value='" + $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").attr('data-select') ) + "']").attr('selected','selected'); <?php
                        break;
                    case 'checkbox': ?>
                        if( $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").attr('data-select') ) == '1' ) {
                            $("input[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]']").each(function() { this.checked = true; });
                        } else {
                            $("input[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]']").each(function() { this.checked = false; });
                        } <?php
                        break;
                    case 'textarea': ?>
                        $("textarea[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]']").val( $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").text() ) ); <?php
                        break;
                    case 'file':
                    case 'password':
                    case 'time_form':
                        break;
                    case 'multiple_cor': ?>
                        var selecoes = $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").attr('data-select') ).split(',');

                        $.each(selecoes, function(index, value){
                            if(value != '') {
                                // $("select[name='data[<?php echo $coluna['coluna'];?>][<?php echo $coluna['coluna'];?>][]']option[value='" + value + "']").attr('selected','selected');
                                $("select[name='data[<?php echo $coluna['coluna'];?>][<?php echo $coluna['coluna'];?>][]'] > option").each(function(k,o){
                                    if(value == o.value) { 
                                        o.selected = true;
                                    }
                                });
                            }
                        }); <?php
                        break;
                    default: 
                        if(!is_array($coluna['coluna'])) {?>
                            $("input[name='data[<?php echo $coluna['dominio'];?>][<?php echo $coluna['coluna'];?>]']").val( $.trim( $("#registro_" + $(this).attr('val_id') + " td.<?php echo $coluna['coluna'];?>").text() ) ); <?php
                        }
                }
                
            endforeach;
            ?>
            $(".chosen-select").trigger("chosen:updated");
            
        });
        
        $("#list_crud tbody tr td div a.modal-confirm").on(ace.click_event, function() {
            
    <?php
        if( $funcaoValidaExclusao != '' ) {
            echo 'if(! ' . $funcaoValidaExclusao . '( $(this) ) ) { return false; } ';
        }
    ?>
            
            var id_registro = $(this).attr('val_id');
            bootbox.confirm({
                    size: 'small',
                    message: "Confirma a exclusão do Registro?",
                    buttons: {
                      confirm: {
                             label: "Sim",
                             className: "btn-primary btn-sm",
                      },
                      cancel: {
                             label: "Não",
                             className: "btn-sm",
                      }
                    },
                    callback: function(result) {
                                debugger;
                            if(result) {
                                $("#<?php echo $dominioPrincipal; ?>Id").val(id_registro);
                                $('#form_crud').attr('action', "<?php echo $this->base; ?>/<?php echo $this->params['controller']; ?>/delete").submit();
                            }
                    }
              })
        });
        
        $(".confirmaMensagem").on(ace.click_event, function() {
            var grupos = "";
            $( "#GrupoGrupo option:selected" ).each(function() {
                if(grupos != '') {
                    grupos += ",";
                }
                grupos += $( this ).val();
            });
            
            if(grupos == '') {
                bootbox.alert("<br><br>Selecione ao menos um Grupo!<br><br>");
            } else {
                
                var url_gp = "<?php echo $this->base; ?>/grupos/countcontatos/";
                $.ajax({
                    type:"POST",
                    url:url_gp,
                    data:{
                        "data[grupos]":grupos
                    },
                    success:function(totalContatosGrupos){
                        if(totalContatosGrupos <=0) {
                            bootbox.alert("<br><br>Não foi possível cadastrar a Mensagem pois o Grupo selecionado não possue Contatos cadastrados.<br>Selecione outro Grupo ou cadastre contatos neste Grupo!<br><br>");
                        } else {
                            var totalSmsMensagem   = totalContatosGrupos;
                            $('#MensagemTotal').val(totalContatosGrupos);
                            
                            var url_sd = "<?php echo $this->base; ?>/mensagens/validaSaldoSms/";
                            var d = new Date();

                            var month = d.getMonth()+1;
                            var day = d.getDate();

                            var output = (day<10 ? '0' : '') + day + '/' + (month<10 ? '0' : '') + month + '/' + d.getFullYear();

                            $.ajax({
                                type:"POST",
                                url:url_sd,
                                data:{
                                    "data[envios]": $('#MensagemEnvio').val() == undefined ? output : $('#MensagemEnvio').val(),
                                    "data[total_contatos]":totalContatosGrupos
                                },
                                success:function(validaSaldo){
                                    if( validaSaldo == "false" ) {
                                        bootbox.alert("<br><br>Não foi possível cadastrar a Mensagem pois o Saldo de SMS é Insuficiênte.<br>Total de SMS desta Mensagem: <b>" + totalSmsMensagem + "</b><br><br>");
                                    } else {
                                        bootbox.confirm({
                                            size: 'small',
                                            message: '<span class="text-warning bigger-110 orange"><br><br><br><br><i class="ace-icon fa fa-exclamation-triangle"></i> Confirma o envio de <b>' + totalSmsMensagem + '</b> mensagens?<br><br><br><br></span>',
                                            buttons: {
                                              confirm: {
                                                     label: "Sim",
                                                     className: "btn-primary btn-sm",
                                              },
                                              cancel: {
                                                     label: "Não",
                                                     className: "btn-sm",
                                              }
                                            },
                                            callback: function(result) {
                                                    if(result) {
                                                        $('#form_crud').submit();
                                                    }
                                            }
                                        })
                                    }
                                }
                            });
                            
                        }
                  
                    }
                });
            }
            // bootbox.alert("<br><br>Sistema em MANUTENÇÃO!<br><br>");
        });
        
        function validaExclusaoGrupo(registro) {
            if( $.trim( $("#registro_" + registro.attr('val_id') + " td.contatos").text() ) > 0 ) {
                bootbox.alert("<br><br>Não foi possível excluir o registro pois o mesmo encontra-se vinculado à outros registros!<br><br>");
                return false;
            } else {
                return true;
            }
        }
        
        function validaExclusaoMensagem(registro) {
            if( $.trim( $("#registro_" + registro.attr('val_id') + " td.envio span").text() ) == 'ENVIADA' ) {
                bootbox.alert("<br><br>Não é possível excluir uma mensagem que já foi enviada!<br><br>");
                return false;
            } else {
                return true;
            }
        }
        
    <?php
        if($threaded) {
    ?>
        $('.tree').treegrid({
            'initialState': 'collapsed',
            'saveState': true,
            treeColumn: <?php echo $treeColumn; ?>
        });
    <?php
        }
    ?>
        
        //$(".chosen-select").chosen({width: "inherit"});
        $('[data-rel="tooltip"]').tooltip({html:true});
        $('[data-rel="popover"]').popover({html:true});
        
        function tooltip_placement(context, source) {
                var $source = $(source);
                var $parent = $source.closest('table')
                var off1 = $parent.offset();
                var w1 = $parent.width();

                var off2 = $source.offset();
                //var w2 = $source.width();

                if( parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2) ) return 'right';
                return 'left';
        }
        
    })
    
    $( "#ContatoTelefone" ).on('focusout', function(e) {
        var id  = $("#ContatoId").val();
        if( id == '' || id <= 0 ) {
            $.getJSON("<?php echo $this->base; ?>/contatos/getContato/"+$(this).val(), function(data){
                if( !(typeof data.Contato == "undefined") ) {

                    bootbox.alert({
                        size: 'small',
                        message: '<span class="text-warning bigger-110 orange"><br><br><br><br><i class="ace-icon fa fa-exclamation-triangle"></i> O telefone informado já encontra-se cadastrado neste Cliente.<br>Nome: ' + data.Contato.nome + '<br>Sobrenome: ' + data.Contato.sobrenome + '<br><br>Caso não esteja aparecendo na sua listagem, avise ao GESTOR!.<br></span>'
                    })

                }
            });
        }
    });
    
    $("#CheckboxAllGrupos").bind('click', function(e) {
        if($(this).is(":checked")) {
            $("#GrupoGrupo option").prop('selected', true);
        } else {
            $("#GrupoGrupo option").prop('selected', false);
        }
        $("#GrupoGrupo").trigger("chosen:updated");
    });
    
</script>

<?php
    }
    
    function getCorLabel($coluna, $registro) {
        if( isset($coluna['concatenar'] ) && $coluna['concatenar']['tipo'] == 'cor' ) {
            return $this->getCor($registro[$coluna['dominio']][$coluna['concatenar']['coluna']], $registro[$coluna['dominio']]['nome'] );
        }
    }
    
    function getCor($cor, $nome = '') {
        return '&nbsp;&nbsp;<span class="badge tooltip-info" data-rel="tooltip" title="' . $nome . '" style="background-color: ' . $cor . '">&nbsp;&nbsp;</span>&nbsp;&nbsp;';
    }
    
    function getLabelDatetimeNow($datetime, $total, $sucesso, $erro, $data,$registro = array()) {
        $class = 'green';
        $texto = $data['menor'];
        $title = $datetime;
        $envio  = new DateTime( $this->Funcoes->formateDateTime( $datetime ) );
        $agora  = new DateTime('now');
        $agora->add(new DateInterval("PT10M"));
        $bool = true;
        if (!empty($registro)) {
            // echo "<pre>";print_r($registro);echo "</pre>";
            $total = count($registro);
            if($total <> 0) {
                foreach ($registro as $r) {
                    if($bool && $envio > $agora) {
                        $bool = false;
                        $class = 'orange';
                        $texto = $data['maior'];                        
                    } else if($bool && $r['success'] <> 1) {
                        $bool = false;
                        $class = 'redColor';
                        $texto = "ALGUMAS MENSAGENS NÃO FORAM ENVIADAS AGUARDE ALGUNS INSTANTES";
                    }
                }
            }
            return '<span class="' . $class . ' tooltip-info" data-rel="tooltip" title="' . $title . '<br>Total SMS: ' . $total . '">' . $texto . '</span>';
        }
        /*$total = 
        $class = 'green';
        $texto = $data['menor'];
        $title = $datetime;
        $envio  = new DateTime( $this->Funcoes->formateDateTime( $datetime ) );
        $agora  = new DateTime('now');
        $agora->add(new DateInterval("PT10M"));
        if($envio > $agora) {
            $class = 'orange';
            $texto = $data['maior'];
        }*/
        
        //return '<span class="' . $class . ' tooltip-info" data-rel="tooltip" title="' . $title . '<br>Total SMS: ' . $total . '<br>Recebido: ' . $sucesso . '<br>Não Recebido: ' . $erro . '">' . $texto . '</span>';
        return '<span class="' . $class . ' tooltip-info" data-rel="tooltip" title="' . $title . '<br>Total SMS: ' . $total . '">' . $texto . '</span>';
    }
    
    function getLabelCheckbox($valor) {
        if($valor == '1') {
            return 'SIM';
        } else {
            return 'NÃO';
        }
    }
    
    function imprimirTr($registro, $dominioPrincipal, $colunas, $parent_id = 0) {

        $id = $registro[$dominioPrincipal]['id'];
        
        $class_tree     = '';
        if(isset( $registro['children'] ) || $parent_id > 0) {
            $class_tree     = "treegrid-" . $id;
        }
        $class_tree_pai = '';
        if($parent_id > 0) {
            $class_tree_pai = "treegrid-parent-" . $parent_id;
        }
?>
        <tr class="<?php echo $class_tree . ' ' . $class_tree_pai;?>" id="registro_<?php echo $id;?>">
        <?php
        foreach ($colunas as $coluna) :
            $dataSelect      = '';
            $descricaoColuna = '';
            $class           = '';
            
            if($coluna['tipo'] == 'hidden_select' || $coluna['tipo'] == 'hidden_datetime' || $coluna['tipo'] == 'hidden' || $coluna['tipo'] == 'hidden_cor' || $coluna['tipo'] == 'password' || $coluna['tipo'] == 'file' || $coluna['tipo'] == 'time_form'){
                $class  = 'hidden';
            }
            
            switch ($coluna['tipo']):
                case 'multiple_cor':
                    $dataSelect = ' data-select="';
                    foreach ($registro[$coluna['coluna']] as $filho) :
                        $descricaoColuna .= $this->getCor($filho['cor'], $filho['nome']);
                        $dataSelect .= $filho['id'] . ',';
                    endforeach;
                    $dataSelect .= '"';
                    break;
                case 'select':
                    $valor  = '';
                    if( isset( $registro[$coluna['dominio']][$coluna['coluna']] ) ) {
                        $valor = $registro[$coluna['dominio']][$coluna['coluna']];
                    }
                    $dataSelect = ' data-select="' . $valor . '"';
                    if(isset($coluna['select'])) {
                        if(isset( $registro[$coluna['select']['dominio']][$coluna['select']['coluna']] )) {
                            $descricaoColuna = $registro[$coluna['select']['dominio']][$coluna['select']['coluna']];
                        }
                    } else {
                        if( isset($registro[$coluna['dominio']][$coluna['coluna']]) && $registro[$coluna['dominio']][$coluna['coluna']] != '' && isset($coluna['data'][$registro[$coluna['dominio']][$coluna['coluna']]]) ) {
                            $descricaoColuna = $coluna['data'][$registro[$coluna['dominio']][$coluna['coluna']]];
                        } else {
                            $descricaoColuna = '';
                        }
                    }
                    break;
                case 'datetime_now':
                    $descricaoColuna = $this->getLabelDatetimeNow($registro[$coluna['dominio']][$coluna['coluna']], $registro[$coluna['dominio']]['total'], $registro[$coluna['dominio']]['sucesso'], $registro[$coluna['dominio']]['erro'], $coluna['data'],$registro['Agendamento']);
                    break;
                case 'checkbox':
                    $dataSelect = ' data-select="' . $registro[$coluna['dominio']][$coluna['coluna']] . '"';
                    $descricaoColuna = $this->getLabelCheckbox($registro[$coluna['dominio']][$coluna['coluna']]);
                    break;
                case 'time_form':
                case 'password':
                case 'checkenbox_disable':
                case 'file':
                    break;
                default :
                    if(is_array($coluna['coluna'])) {
                        $contador = 0;
                        foreach ($coluna['coluna'] as $colunaConcatenar) {
                            if($contador > 0) {
                                $descricaoColuna .= $coluna['separador'];
                            }
                            $descricaoColuna .= $registro[$coluna['dominio']][$colunaConcatenar];
                            $contador++;
                        }
                    } else {
                        $descricaoColuna = $this->getCorLabel($coluna, $registro) . $registro[$coluna['dominio']][$coluna['coluna']];
                    }
            endswitch;
                        
            if(!is_array($coluna['coluna'])) {
                $class  .= ' ' . $coluna['coluna'];
            }
            if ( isset( $coluna['class'] ) ) {
                $class .= ' ' . $coluna['class'];
            }
            if(!in_array($coluna['tipo'],array('checkenbox_disable'))) { 
                echo '<td ' . $dataSelect . ' class="' . $class . '">' . $descricaoColuna;
                echo '</td>';
            }
        endforeach;
        ?>
            <td>
                    <!--div class="hidden-sm hidden-xs action-buttons"-->
                    <div class="action-buttons">
                      <?php
                      
                        $exibiBotoes = true;
                        if( isset($registro[$coluna['dominio']]['cliente_id']) && $this->opcoes['verificaUsuarioParaAtualizar'] && $registro[$coluna['dominio']]['usuario_id'] != AuthComponent::user('id') && AuthComponent::user('role') == 'subordinado') {
                            $exibiBotoes = false;
                        }
                      
                        if($this->opcoes['habilitarEdicao'] && $exibiBotoes) {
                      ?>
                        <a val_id="<?php echo $registro[$coluna['dominio']]['id']; ?>" class="editar_registro green" href="#modal-form" data-toggle="modal"><i class="ace-icon fa fa-pencil bigger-130"></i></a>
                      <?php
                        }
                        if(empty($registro['children']) && $exibiBotoes) {
                      ?>
                        <a val_id="<?php echo $registro[$coluna['dominio']]['id']; ?>" class="modal-confirm red" href="#"><i class="ace-icon fa fa-trash-o bigger-130"></i></a>
                      <?php
                        }
                        if(isset($this->opcoes['removeadd']) && $this->opcoes['removeadd'] && $exibiBotoes) {
                      ?>
                      <a href="grupos/removeadd/<?php echo $registro[$coluna['dominio']]['id']; ?>"><i class="fa fa-chain-broken" aria-hidden="true"></i></a>
                      <?php
                        }
                        ?>
                    </div>

<!--                    <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-primary dropdown-toggle" data-toggle="dropdown" data-position="auto">
                                    <i class="ace-icon fa fa-cog icon-only bigger-110"></i>
                            </button>

                            <ul class="dropdown-menu dropdown-only-icon dropdown-yellow dropdown-menu-right dropdown-caret dropdown-close">
                                    <li>
                                        <a href="#" class="tooltip-info" data-rel="tooltip" title="Ver">
                                                <span class="blue">
                                                        <i class="ace-icon fa fa-search-plus bigger-120"></i>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="tooltip-success" data-rel="tooltip" title="Editar">
                                                <span class="green">
                                                        <i class="ace-icon fa fa-pencil-square-o bigger-120"></i>
                                                </span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="#" class="tooltip-error" data-rel="tooltip" title="Deletar">
                                                <span class="red">
                                                        <i class="ace-icon fa fa-trash-o bigger-120"></i>
                                                </span>
                                        </a>
                                    </li>
                            </ul>
                        </div>
                    </div>-->
            </td>
        </tr>
<?php
        if(isset($registro['children'])) {
            foreach ($registro['children'] as $filho):
                $this->imprimirTr($filho, $dominioPrincipal, $colunas, $id);
            endforeach;
        }
    }
}
?>
