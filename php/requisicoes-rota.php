<?php

require_once "classConexao.php";

$cn = new db();
$cn->conectar();

if(isset($_POST['rota_pontos'])){
    $id = $_POST['rota_pontos'];
    $sql="SELECT rota.latitude, rota.longitude FROM rota WHERE rota.bdvID=:bdvID ORDER BY rota.cont";
    $parametros = array(':bdvID'=>$id);
    $source = $cn->executa_sql($sql, $parametros); 
    if ( $cn->qtde_registros($source) == 0 ){
        echo "0#";
    }else{
        while( $dados = $cn->extrai($source)){			
            $row_s[] = (array)$dados;	
        }    
        echo json_encode($row_s);
    }
}

else if(isset($_POST['rota_info'])){
    $id = $_POST['rota_info'];
    $sql = "SELECT bdv.motoristaNome, bdv.frota_veiculo, bdv.hora_inicial, bdv.hora_final, bdv.km_total, bdv.km_calculado, bdv.km_rodovia, bdv.km_cidade, bdv.velocidade_media, bdv.placa_reserva FROM bdv WHERE bdv.bdvID = :bdvID";
    $parametros = array(':bdvID'=>$id);
    $source = $cn->executa_sql($sql, $parametros);
    if ( $cn->qtde_registros($source) == 0 ){
        echo "0#";
    }else{
        while( $dados = $cn->extrai($source)){			
            $row_s[] = (array)$dados;	
        }    
        echo json_encode($row_s);
    }
}

?>