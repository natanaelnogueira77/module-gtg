const chartEventClick = function(e) {
    var x      = e.xAxis[0].value;
    var val    = Math.ceil(e.yAxis[0].value);
    var ini    = -0.5;
    var loop   = true;
    var indice = 0;
    
    recordOutboundLink('ClicouRodaDaVida');
    
    if(val > 10) {
        return;
    }
    
    while(loop) {
        if(x >= ini && x < (ini + 1)) {
            loop = false;
        } else {
            ini++;
            indice++;
        }
    }
    
    $elem.highcharts().series[0].data[indice].update({
        y : val
    });
    
    onChangeValue(indice, val);
};

$elem.highcharts({
    credits: {
        enabled: false
    },
    exporting: {
        buttons: {
            contextButton: {
                enabled: false
            },
            printButton: {
                enabled: false
            }
        }
    },
    chart: {
        polar: true,
        width: widthGrafico,
        height: heightGrafico,
        backgroundColor: null,
        zoomType: false,
        margin: [0, 0, 0, 0],
        spacingTop: 0,
        spacingBottom: 0,
        spacingLeft: 0,
        spacingRight: 0,
        style: {
            fontFamily: 'Raleway',
            fontWeight: 'bold'
        },
        events: {
            load: function(event) {
                adjustLabels(event);
            },
            resize: function(event) {
                adjustLabels(event);
            },
            click: function(e) {
                chartEventClick(e);
            }
        }
    },
    title: {
        text: title
    },
    pane: {
        size: isMobile() ? '95%' : '80%',
        startAngle: 0,
        endAngle: 360
    },
    xAxis: {
        categories: areas,
        labels: {
            style: {
                fontSize: window.isPhoneDevice ? '12px' : '15px',
                fontWeight: 'bold'
            }
        }
    },
    yAxis: {
        min: 0,
        tickInterval: 1,
        showEmpty: false,
        max: 10,
        gridLineColor: '#cccccc',
        labels: {
            formatter: function() {
                // highchart mostra o primeiro como 0. ForÃ§amos o formato como 1
                return this.value + 1;
            }
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0,
            groupPadding: 0
        }
    },
    tooltip: {
        followPointer: false,
        followTouchMove: false
    },
    series: [{
        type: 'column',
        name: 'Valor',
        showInLegend: false,
        allowDecimals: false,
        events: {
            click: function(e){
            seriesEventClick(e);
            }
        },
        data: getDataChartRodaDaVida(window.areasValor)
    }]
});