<?php

require_once "classConexao.php";

$cn = new db();
$cn->conectar();

if(isset($_POST['opx1'])){
    $_data = [];
    for($i=1; $i<13; $i++){
        $sql = "SELECT SUM(bdv.km_total) AS soma_km FROM bdv WHERE MONTH(bdv.hora_inicial)=".$i.";";
        $source = $cn->executa_sql($sql);
        $_data[$i] = (array)$cn->extrai($source); 
    }
    echo json_encode($_data);
}
else if(isset($_POST['opx2'])){
    $_data = [];
    $sql1 = "SELECT SUM(bdv.km_total) AS soma_km FROM bdv;";
    $source = $cn->executa_sql($sql1);
    $_data['soma_km'] = (array)$cn->extrai($source);

    $sql2 = "SELECT COUNT(bdv.bdvID) AS total_bdv FROM bdv;";
    $source = $cn->executa_sql($sql2);
    $_data['total_bdv'] = (array)$cn->extrai($source);

    $sql3 = "SELECT COUNT(assinatura.assinaturaID) AS total_passageiros FROM assinatura;";
    $source = $cn->executa_sql($sql3);
    $_data['total_passageiros'] = (array)$cn->extrai($source);

    echo json_encode($_data);
}

?>