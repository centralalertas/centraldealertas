<?php
    echo $this->Html->script( 'highcharts/highcharts' );
    echo $this->Html->script( 'highcharts/highcharts-3d' );
    echo $this->Html->script( 'highcharts/highcharts-more' );
    echo $this->Html->script( 'highcharts/modules/solid-gauge' );
?>

<div class="row">
    <div class="col-xs-6">
        <div id="sms_por_semana" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-xs-6">
        <div id="envios_mes" style="width: 450px; height: 300px;"></div>
    </div>
</div>
    <?php
        $clientesSms = "";
        $nomeSemana1 = "";
        $dataSemana1 = "";
        $nomeSemana2 = "";
        $dataSemana2 = "";
        $nomeSemana3 = "";
        $dataSemana3 = "";
        $nomeSemana4 = "";
        $dataSemana4 = "";
        
        foreach ($clientesComSms  as $cliente) {
            if($clientesSms != '') {
                $clientesSms .= ",";
            }
            $clientesSms .= "'" . $cliente['Cliente']['nome'] . "'";
            
            $contaSemana = 1;
            foreach ($cliente['Cliente']['dados'] as $semana => $quantidade) {
                $nomeSemana = 'nomeSemana' . $contaSemana;
                $dataSemana = 'dataSemana' . $contaSemana;
                
                $$nomeSemana = 'Sem (' . $semana . ')';

                if($$dataSemana != '') {
                    $$dataSemana .= ",";
                }
                $$dataSemana .= $quantidade;
                
                $contaSemana++;
            }            
        }
    ?>
<script type="text/javascript">
    $('#sms_por_semana').highcharts({
        chart: {
            type: 'bar',
            options3d: {
                enabled: true,
                alpha: 10,
                beta: 25,
                depth: 70
            }
        },
        title: {
            text: 'Envios nas Últimas 4 Semanas'
        },
        xAxis: {
            categories: [<?php echo $clientesSms; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'SMS',
            },
            labels: {
                overflow: 'justify'
            }
        },
        plotOptions: {
            bar: {
                dataLabels: {
                    enabled: true
                }
            }
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'top',
            x: -40,
            y: 80,
            floating: true,
            borderWidth: 1,
            backgroundColor: ((Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'),
            shadow: true
        },
        credits: {
            enabled: false
        },

        series: [{
            name: '<?php echo $nomeSemana4; ?>',
            data: [<?php echo $dataSemana4; ?>]
            }, {
            name: '<?php echo $nomeSemana3; ?>',
            data: [<?php echo $dataSemana3; ?>]
            }, {
            name: '<?php echo $nomeSemana2; ?>',
            data: [<?php echo $dataSemana2; ?>]
            }, {
            name: '<?php echo $nomeSemana1; ?>',
            data: [<?php echo $dataSemana1; ?>]
        }]
    });
    
    var gaugeOptions = {

        chart: {
            type: 'solidgauge'
        },

        title: null,

        pane: {
            center: ['50%', '85%'],
            size: '140%',
            startAngle: -90,
            endAngle: 90,
            background: {
                backgroundColor: (Highcharts.theme && Highcharts.theme.background2) || '#EEE',
                innerRadius: '60%',
                outerRadius: '100%',
                shape: 'arc'
            }
        },

        tooltip: {
            enabled: false
        },

        // the value axis
        yAxis: {
            stops: [
                [0.1, '#55BF3B'], // green
                [0.5, '#DDDF0D'], // yellow
                [0.9, '#DF5353'] // red
            ],
            lineWidth: 0,
            minorTickInterval: null,
            tickPixelInterval: 400,
            tickWidth: 0,
            title: {
                y: -125
            },
            labels: {
                y: 16
            }
        },

        plotOptions: {
            solidgauge: {
                dataLabels: {
                    y: 5,
                    borderWidth: 0,
                    useHTML: true
                }
            }
        }
    };
    
    $('#envios_mes').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: <?php echo $totalSmsVendido; ?>,
            title: {
                text: '<div style="text-align:center"><span style="font-size:18px;color:#333333">Envios no Mês</span></div>'
            }
        },

        credits: {
            enabled: false
        },

        series: [{
            name: 'SMS',
            data: [<?php echo $totalSmsMesAtual; ?>],
            dataLabels: {
                format: '<div style="text-align:center"><span style="font-size:25px;color:' +
                    ((Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black') + '">{y}</span><br/>' +
                       '</div>'
            },
            tooltip: {
                valueSuffix: 'Qdt'
            }
        }]

    }));
</script>

<br>
<div class="row">
        <div class="col-xs-12">
            <?php
                $colunas    = array( 
                    array(
                        'nome' => 'Nome',
                        'dominio' => 'Cliente',
                        'coluna' => 'nome',
                        'tipo' => 'text'
                    ),
                    array(
                        'nome' => 'CNPJ/CPF',
                        'dominio' => 'Cliente',
                        'coluna' => 'cnpj',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Endereço',
                        'dominio' => 'Cliente',
                        'coluna' => 'endereco',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Responsável',
                        'dominio' => 'Cliente',
                        'coluna' => 'responsavel',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Email',
                        'dominio' => 'Cliente',
                        'coluna' => 'email',
                        'tipo' => 'text',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Telefone',
                        'dominio' => 'Cliente',
                        'coluna' => 'telefone',
                        'tipo' => 'phone',
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'SMS Utilizado',
                        'dominio' => 'Cliente',
                        'coluna' => array('sms_utilizado','sms_mes'),
                        'separador' => ' / ',
                        'tipo' => 'text'
                    ),
                    array(
                        'nome' => 'SMS no Mês',
                        'dominio' => 'Cliente',
                        'coluna' => 'sms_mes',
                        'tipo' => 'text',
                        'class' => 'hidden'
                    ),
                    array(
                        'nome' => 'Seguimento',
                        'dominio' => 'Cliente',
                        'coluna' => 'seguimento',
                        'tipo' => 'select',
                        'data' => $seguimentos,
                        'class' => 'hidden-sm hidden-xs'
                    ),
                    array(
                        'nome' => 'Logo do Cliente',
                        'dominio' => 'Cliente',
                        'coluna' => 'logo',
                        'tipo' => 'file',
                        'class' => 'hidden-sm hidden-xs'
                    )
                );
                echo $this->Tabela->imprimir($colunas, $clientes, 'Cliente', array('validationErrors'=>$this->validationErrors, 
                                                                                'formOpcoes'=>array('id'=>'form_crud', 'type'=>'file')) );
            ?>
        </div>
</div>

<?php
    echo $this->element('rodape_tabela', array('threaded'=>false));
?>