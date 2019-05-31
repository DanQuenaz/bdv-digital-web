

$.ajax({
    method: "POST",
    url: "./php/requisicoes-graficos.php",
    data: { opx1: "xxx"}
}).done(function( msg ) {
        var _data = JSON.parse(msg);
        var aux = [];

        for(i=1; i<13; i++){
            if(_data[i].soma_km == null)aux[i-1]=0;
            else aux[i-1] = parseFloat(_data[i].soma_km);
        }

        var dados_grafico_linha = {
            labels : ["Jan","Fev","Mar","Abr","Mai","Jun","Jul", "Ago", "Set", "Out", "Nov", "Dez"],
            datasets : [
                {
                    label: "My First dataset",
                    fillColor : "rgba(48, 164, 255, 0.2)",
				    strokeColor : "rgba(48, 164, 255, 1)",
				    pointColor : "rgba(48, 164, 255, 1)",
                    pointStrokeColor : "#fff",
                    pointHighlightFill : "#fff",
                    pointHighlightStroke : "rgba(220,220,220,1)",
                    data : aux
                }
            ]
        }

        var chart1 = document.getElementById("line-chart").getContext("2d");
			window.myLine = new Chart(chart1).Line(dados_grafico_linha, {
                responsive: true,
                scaleLineColor: "rgba(0,0,0,.2)",
                scaleGridLineColor: "rgba(0,0,0,.05)",
                scaleFontColor: "#c5c7cc"
			});
    });


$.ajax({
    method: "POST",
    url: "./php/requisicoes-graficos.php",
    data: { opx2: "xxx"}
}).done(function( msg ) {
        var _data = JSON.parse(msg);
        var soma_km = parseFloat(_data['soma_km'].soma_km);
        $("#info_total_km").html(parseFloat(soma_km.toFixed(2)));
        $("#info_total_bdv").html(_data['total_bdv'].total_bdv);
        $("#info_total_passageiros").html(_data['total_passageiros'].total_passageiros);
    });