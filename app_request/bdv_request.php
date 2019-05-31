<?php
require_once "classConexaoDB.php";
require_once "classService.php";

$cn = new db();
$cn->conectar();

$sv = new service($cn);

date_default_timezone_set('America/Sao_Paulo');

//****************ATUALIZAÇÃO LISTA DE MOTORISTAS**************************/
if(isset($_POST['OPX_GET_MTR'])){//Checa se existe parametros na requisição
   echo $sv->retornaMotoristas();
}

//**************REQUISIÇÃO BDVS******************//	
else if(isset($_POST['OPX_SET_BDV'])){
	$bdvs = json_decode($_POST['OPX_SET_BDV']);
	echo $sv->insereBDV($bdvs);
}

//**************REQUISIÇÃO CHECK LIST******************//	
else if(isset($_POST['OPX_SET_CKL'])){
	$_dados = json_decode($_POST['OPX_SET_CKL']);
	echo $sv->insereCheckList($_dados);
}

//**************REQUISIÇÃO HORA EXTRA******************//	
else if(isset($_POST['OPX_SET_HRE'])){
	$_dados = json_decode($_POST['OPX_SET_HRE']);
	echo $sv->insereHoraExtra($_dados);
}

//**************REQUISIÇÃO CUSTOS MOTORISTA******************//	
else if(isset($_POST['OPX_SET_CSM'])){
	$_dados = json_decode($_POST['OPX_SET_CSM']);
	echo $sv->insereCustosMotorista($_dados);
}

/***************VERIFICAÇÃO SENHA ADMINISTRADOR*************/
else if(isset($_POST['OPX_GET_PSWRD'])){
	$senha = $_POST['OPX_GET_PSWRD'];
	echo $sv->checaSenhaADM($senha);
}

/***************VERIFICAÇÃO SENHA ADMINISTRADOR*************/
else if(isset($_POST['OPX_SET_MTR_PSWRD'])){
	$_dados = json_decode($_POST['OPX_SET_MTR_PSWRD']);
	echo $sv->alteraSenhaMotorista($_dados);
}




// else{
// 	$sql = "SELECT * FROM bdv";
// 	$source = $cn->executa_sql($sql);

// 	if ( $cn->qtde_registros($source) == 0 ){			
// 		echo "NOT_OK#";
// 	}else{
// 		while( $dados = $cn->extrai($source)){	
// 			//var_dump($dados);	
			
// 			echo "<img src='data:image/jpeg;base64, $dados->foto' />";

			
// 		}
// 	}
// }

?>