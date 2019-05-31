<?php
require_once "classConexao.php";

$cn = new db();
$cn->conectar();

if(isset($_POST['opt_localidade'])){
    $row_s = array();
    $sql = "SELECT localidade.nome FROM localidade";
    $source = $cn->executa_sql($sql);

    while( $dados = $cn->extrai($source) ){
        $row_s[] = (array)$dados;
    }

    echo json_encode($row_s);
}

else if(isset($_POST['opt_resultados_iniciais'])){
    $row_s = array();
    $sql = "SELECT DISTINCT motorista.nome, motorista.matricula, motorista.cnh, motorista.vencimento_cnh, motorista.media_avaliacoes FROM motorista LIMIT 20";
    
    $source = $cn->executa_sql($sql);

    while( $dados = $cn->extrai($source) ){
        $row_s[] = (array)$dados;
    }

    echo json_encode($row_s);
}

else if(isset($_POST['opt_resultados_filtrados'])){
    $row_s = array();
    $sql = $_POST['opt_resultados_filtrados'];
    
    $source = $cn->executa_sql($sql);

    while( $dados = $cn->extrai($source) ){
        $row_s[] = (array)$dados;
    }

    echo json_encode($row_s);
}

?>