
$.ajax({
    method: "POST",
    url: "./php/requisicoes.php",
    data: { opt_values_filters: "xxx"}
}).done(function( msg ) {
        _valores = JSON.parse(msg);
        _gerencia = _valores['gerencia'];
        _supervisao = _valores['supervisao'];
        _localidade = _valores['localidade'];
        _regiao = _valores['regiao'];
        _centro_custo = _valores['centro_custo'];

        var outPut = "<option selected>...</option>";
        for(i = 0; i < _gerencia.length; i++){
            outPut += "<option>"+_gerencia[i].nome+"</option>";
        }
        $("#inputGerencia").html(outPut);

        outPut = "<option selected>...</option>";
        for(i = 0; i < _supervisao.length; i++){
            outPut += "<option>"+_supervisao[i].nome+"</option>";
        }
        $("#inputSupervisao").html(outPut);

        outPut = "<option selected>...</option>";
        for(i = 0; i < _localidade.length; i++){
            outPut += "<option>"+_localidade[i].nome+"</option>";
        }
        $("#inputLocalidade").html(outPut);

        outPut = "<option selected>...</option>";
        for(i = 0; i < _regiao.length; i++){
            outPut += "<option>"+_regiao[i].nome+"</option>";
        }
        $("#inputRegiao").html(outPut);

        outPut = "<option selected>...</option>";
        for(i = 0; i < _centro_custo.length; i++){
            outPut += "<option>"+_centro_custo[i].centro_custo+"</option>";
        }
        $("#inputCentroCusto").html(outPut);


    });

$.ajax({
    method: "POST",
    url: "./php/requisicoes.php",
    data: { opt_all: "xxx"}
}).done(function( msg ) {
        var _data = JSON.parse(msg);
        var outPut = ""; 
        for(i = 0; i < _data.length; i++){
            outPut +=
            "<tr>"+
            "<th scope='row'><a href='./info_rota.html?bdv_id="+_data[i].bdvID+"'>"+_data[i].frota_veiculo+"</a></th>"+
            "<td>"+_data[i].motoristaNome+"</td>"+
            "<td>"+_data[i].km_total+"</td>"+
            "<td>"+_data[i].servico+"</td>"+
            "</tr>";
        }
        $(".tableResults").html(outPut);
    });

$('#btnPesquisa').on('click', function(event){
    var gerencia = $('#inputGerencia :selected').text();
    var supervisao = $('#inputSupervisao :selected').text();
    var localidade = $('#inputLocalidade :selected').text();
    var regiao = $('#inputRegiao :selected').text();
    var categoria = $('#inputCategoria :selected').text();
    var centro = $('#inputCentroCusto :selected').text();
    var data_inicio = $('#inputIntervalo1').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();
    var data_fim = $('#inputIntervalo2').datepicker({ dateFormat: 'dd-mm-yyyy' }).val();

    var aux = true;
    var _sql ="SELECT DISTINCT bdv.bdvID, bdv.frota_veiculo, bdv.hora_inicial, bdv.hora_final, bdv.motoristaNome, bdv.servico, bdv.km_total FROM bdv, centro_custo, localidade, regiao, funcionario, frota ";

    if(gerencia != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.frota_veiculo = frota.frota AND frota.centro_custo = centro_custo.centro_custo AND centro_custo.gerente = '"+gerencia+"')";
    }

    if(supervisao != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.frota_veiculo = frota.frota AND frota.centro_custo = centro_custo.centro_custo AND centro_custo.supervisor = '"+supervisao+"')";
    }

    if(localidade != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.frota_veiculo = frota.frota AND frota.centro_custo = centro_custo.centro_custo AND centro_custo.localidade = localidade.localidadeID and localidade.nome = '"+localidade+"')";
    }

    if(regiao != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.frota_veiculo = frota.frota AND frota.centro_custo = centro_custo.centro_custo AND centro_custo.localidade = localidade.localidadeID AND localidade.regiao = '"+regiao+"')";
    }

    if(categoria != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.frota_veiculo = frota.frota AND frota.categoria = '"+categoria+"')";
    }

    if(centro != "..."){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}
        _sql += "(bdv.centro_custo = '"+centro+"')";
    }

    if( (data_inicio != "" && data_fim != "") || (data_inicio == data_fim && data_inicio != "")){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}

        _aux1 = data_inicio.split("-");
        _data1 = _aux1[2] + "-" + _aux1[1] + "-" + _aux1[0] + " 00:00:00";

        _aux2 = data_fim.split("-");
        _data2 = _aux2[2] + "-" + _aux2[1] + "-" + _aux2[0] + " 23:59:59";

        _sql += "(bdv.hora_inicial BETWEEN '"+_data1+"' AND '"+_data2+"')";
    }else if(data_inicio != "" && data_fim == ""){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}

        _aux1 = data_inicio.split("-");
        _data1 = _aux1[2] + "-" + _aux1[1] + "-" + _aux1[0] + " 00:00:00";

        _sql += "(bdv.hora_inicial >= '"+_data1+"')";
    }else if(data_inicio == "" && data_fim != ""){
        if(!aux)_sql += " AND ";
        else  {aux = false; _sql += "WHERE ";}

        _aux2 = data_fim.split("-");
        _data2 = _aux2[2] + "-" + _aux2[1] + "-" + _aux2[0] + " 23:59:59";

        _sql += "(bdv.hora_inicial <= '"+_data2+"')";
    }

    $.ajax({
        method: "POST",
        url: "./php/requisicoes.php",
        data: { opt_sql: _sql}
    }).done(function( msg ) {
            var _data = JSON.parse(msg);
            var outPut = ""; 
            for(i = 0; i < _data.length; i++){
                outPut +=
                "<tr>"+
                "<th scope='row'><a href='./info_rota.html?bdv_id="+_data[i].bdvID+"'>"+_data[i].frota_veiculo+"</a></th>"+
                "<td>"+_data[i].motoristaNome+"</td>"+
                "<td>"+_data[i].km_total+"</td>"+
                "<td>"+_data[i].servico+"</td>"+
                "</tr>";
            }
            $(".tableResults").html(outPut);
        });
    

});


