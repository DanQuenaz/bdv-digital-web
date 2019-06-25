$.ajax({
    method: "POST",
    url: "./php/requisicoes-motorista.php",
    data: { opt_localidade: "xxx"}
}).done(function( msg ) {
        var _data = JSON.parse(msg);
        var outPut = "<option selected>...</option>";
        for(i = 0; i < _data.length; i++){
            outPut += "<option>"+ _data[i].nome +"</option>";
        }
        $("#input_localidade").html(outPut);
    });

    $.ajax({
        method: "POST",
        url: "./php/requisicoes-motorista.php",
        data: { opt_resultados_iniciais: "xxx"}
    }).done(function( msg ) {
            var _data = JSON.parse(msg);
            var outPut = "";
            for(i = 0; i < _data.length; i++){
                outPut +=
                "<tr>"+
                "<th scope='row'><a href='#'>"+_data[i].nome+"</a></th>"+
                "<td>"+_data[i].matricula+"</td>"+
                "<td>"+_data[i].cnh+"</td>"+
                "<td>"+_data[i].vencimento_cnh+"</td>"+
                "<td>"+_data[i].media_avaliacoes+"</td>"+
                "<td> <button type='button' class='btn btn-sm btn-default'>Editar</button> </td>"+
                "</tr>";
            }
            $(".tableResults").html(outPut);
        });

$('#btn_pesquisar').on('click', function(event){
    var aux = true;
    var input_pesquisa = document.getElementById('input_pesquisa').value.toLowerCase();
    var input_localidade = document.getElementById('input_localidade').value;
    var query_sql = "SELECT DISTINCT motorista.nome, motorista.matricula, motorista.cnh, motorista.vencimento_cnh, motorista.media_avaliacoes FROM motorista, centro_custo, funcionario";

    if(input_pesquisa != ''){
        query_sql += " WHERE ( LOWER(motorista.nome) LIKE('%"+input_pesquisa+"%') OR motorista.matricula LIKE('%"+input_pesquisa+"%') OR motorista.cpf LIKE('%"+input_pesquisa+"%') )";

        aux = false;
    }

    if(input_localidade != '...'){
        if(!aux) query_sql += " AND ( motorista.localidade = '"+input_localidade+"' )";
        else query_sql += " WHERE ( motorista.localidade ='"+input_localidade+"' )";
    }

    query_sql += " LIMIT 20 ";

    $.ajax({
        method: "POST",
        url: "./php/requisicoes-motorista.php",
        data: { opt_resultados_filtrados: query_sql}
    }).done(function( msg ) {
            var _data = JSON.parse(msg);
            var outPut = "";
            for(i = 0; i < _data.length; i++){
                outPut +=
                "<tr>"+
                "<th scope='row'><a href='#'>"+_data[i].nome+"</a></th>"+
                "<td>"+_data[i].matricula+"</td>"+
                "<td>"+_data[i].cnh+"</td>"+
                "<td>"+_data[i].vencimento_cnh+"</td>"+
                "<td>"+_data[i].media_avaliacoes+"</td>"+
                "<td> <button type='button' class='btn btn-sm btn-default'>Editar</button> </td>"+
                "</tr>";
            }
            $(".tableResults").html(outPut);
        });
    
});

// SELECT DISTINCT motorista.nome, motorista.matricula, motorista.cnh, motorista.vencimento_cnh, motorista.media_avaliacoes FROM motorista, centro_custo, funcionario WHERE ( LOWER(motorista.nome) LIKE('%%') OR motorista.matricula LIKE('%%') OR motorista.cpf LIKE('%%') ) AND ( motorista.centro_custo = centro_custo.centro_custo AND centro_custo.supervisor = 'Lucio' ) LIMIT 100 