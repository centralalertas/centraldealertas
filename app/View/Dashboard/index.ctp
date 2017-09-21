<?php
    echo $this->Html->script( 'highcharts/highcharts' );
    echo $this->Html->script( 'highcharts/highcharts-3d' );
    echo $this->Html->script( 'highcharts/highcharts-more' );
    echo $this->Html->script( 'highcharts/modules/solid-gauge' );
	
	if ( AuthComponent::user('role') == 'admin' ){
		echo '';
	}
?>
<div class="row">
    <div class="col-xs-6">
        <div id="contatos_por_grupo" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <div class="col-xs-6">
        <div id="sms_6meses" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
</div>
<div class="hr hr32 hr-dotted"></div>
<div class="row">
    <?php if(AuthComponent::user('role') != 'subordinado') { ?>
    <div class="col-xs-6">
        <div id="mensagem_categoria" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
    <?php } ?>
    <div class="col-xs-6">
        <div id="saldo_mes" style="width: 450px; height: 300px;"></div>
    </div>
</div>

<script type="text/javascript">
$(function () {
    $('#sms_6meses').highcharts({
        credits: {
            enabled: false
        },
        title: {
            text: 'Mensagens por Mês',
            x: -20 //center
        },
        xAxis: {
            categories: [<?php echo join($ultimos6Meses['siglas'], ','); ?>]
        },
        yAxis: {
            title: {
                text: ''
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: ''
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [
    <?php
        $cores  = "";
        foreach ($gruposSmsPorMes  as $grupo) {
            if($cores != '') {
                $cores .= ",";
            }
            $cores .= "'" . $grupo['Grupo']['cor'] . "'";
    ?>
        {
            name: '<?php echo $grupo['Grupo']['nome'] ?>',
            data: [<?php echo join($grupo['Grupo']['dados'], ','); ?>]
        }, 
    <?php
        }
    ?>
        ],
        colors: [<?php echo $cores; ?>]
    });
    
    <?php
        $grupos = "";
        $dados  = "";
        $cores  = "";
        
        foreach ($contatosPorGrupo  as $grupo) {
            if($grupos != '') {
                $grupos .= ",";
            }
            $grupos .= "'" . $grupo['ContatoPorGrupo']['nome'] . "'";
            
            if($dados != '') {
                $dados .= ",";
            }
            $dados .= $grupo['ContatoPorGrupo']['quantidade'];
            
            if($cores != '') {
                $cores .= ",";
            }
            $cores .= "'" . $grupo['ContatoPorGrupo']['cor'] . "'";
        }
    ?>
    $('#contatos_por_grupo').highcharts({
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
            text: 'Contatos por Grupo'
        },
        xAxis: {
            categories: [<?php echo $grupos; ?>],
            title: {
                text: null
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Quantidade',
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
            name: 'Contatos',
            data: [<?php echo $dados; ?>],
            colorByPoint: true,
            colors: [<?php echo $cores; ?>]
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

    // The speed gauge
    $('#saldo_mes').highcharts(Highcharts.merge(gaugeOptions, {
        yAxis: {
            min: 0,
            max: <?php $cliente = AuthComponent::user('Cliente'); echo $cliente['sms_mes']; ?>,
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
    
<?php if(AuthComponent::user('role') != 'subordinado') { ?>
    $('#mensagem_categoria').highcharts({
        credits: {
            enabled: false
        },
        chart: {
            type: 'pie',
            options3d: {
                enabled: true,
                alpha: 45
            }
        },
        title: {
            text: 'Mensagens por Assunto'
        },
        plotOptions: {
            pie: {
                innerSize: 100,
                depth: 45
            }
        },
        series: [{
            name: 'Mensagens',
            data: [
            <?php
            foreach ($mensagensPorCategoria as $categoria) {
            ?>
                ['<?php echo $categoria['MensagemPorCategoria']['nome']; ?>', <?php echo $categoria['MensagemPorCategoria']['quantidade']; ?>],
            <?php
            }
            ?>
            ]
        }]
    });
<?php } ?>
    
});
</script>
