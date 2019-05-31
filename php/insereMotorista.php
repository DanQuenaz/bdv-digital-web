<?php

require_once "classConexao.php";

$cn = new db();
$cn->conectar();

$nome = $_POST['input_nome'];
$matricula = $_POST['input_matricula'];
$cpf = $_POST['input_cpf'];
$rg = $_POST['input_rg'];
$cnh = $_POST['input_cnh'];
$vencimento_cnh = $_POST['input_vencimento_cnh'];
$localidade = $_POST['input_localidade'];
$media_avaliacoes = "0";
$senha = "12345";
$cadastrar = $_POST['btn_cadastrar'];

$aux = explode("-", $vencimento_cnh);
$data_aux = $aux[2]."-".$aux[1]."-".$aux[0]." 23:59:59";

if(isset($cadastrar)){
    $cn->iniciaTransacao();	
    try{
        $sql = "INSERT INTO `base_bdv`.`motorista` (`motoristaID`, `matricula`, `nome`, `rg`, `cpf`, `cnh`, `vencimento_cnh`, `localidade`, `media_avaliacoes`, `senha`) VALUES (NULL, :matricula, :nome, :rg, :cpf, :cnh, :vencimento_cnh, :localidade, :media_avaliacoes, :senha)";
        $parametros = array(':matricula'=>$matricula,
                            ':nome'=>$nome,
                            ':rg'=>$rg,
                            ':cpf'=>$cpf,
                            ':cnh'=>$cnh,
                            ':vencimento_cnh'=>$data_aux,
                            ':localidade'=>$localidade,
                            ':media_avaliacoes'=>$media_avaliacoes,
                            ':senha'=>$senha
                            );
        $cn->executa_sql($sql, $parametros);
        $cn->ConfirmaTransacao();
        $cn = null;
        echo"<script language='javascript' type='text/javascript'>
                alert('Motorista cadastrado com sucesso!');
                window.location.href='../motoristas.html';
            </script>";
    }catch(PDOException $e){
        echo "0#*".$e->getMessage();
        $cn->CancelaTransacao();
        $cn = null;
        exit;
    }
}

?>