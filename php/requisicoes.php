<?php
    require_once "classConexao.php";

    $cn = new db();
    $cn->conectar();

    if(isset($_POST['opt_all'])){
        
        $row_s = array();
        $sql = "SELECT * FROM bdv";
        $source = $cn->executa_sql($sql);
        
		if ( $cn->qtde_registros($source) == 0 ){
			echo "0#";
		}else{
			while( $dados = $cn->extrai($source)){			
				$row_s[] = (array)$dados;	
            }    
            echo json_encode($row_s);
		}

    }else if(isset($_POST['opt_values_filters'])){
        $filtros = array();
        $filtro = array();

        $sql = "SELECT funcionario.nome FROM funcionario WHERE funcao = 'Gerente'";
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $filtro[] = (array)$dados;
        }
        $filtros['gerencia'] = $filtro;
        $filtro = array();

        $sql = "SELECT funcionario.nome FROM funcionario WHERE funcao = 'Supervisor'";
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $filtro[] = (array)$dados;
        }
        $filtros['supervisao'] = $filtro;
        $filtro = array();

        $sql = "SELECT localidade.nome FROM localidade";
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $filtro[] = (array)$dados;
        }
        $filtros['localidade'] = $filtro;
        $filtro = array();

        $sql = "SELECT regiao.nome FROM regiao";
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $filtro[] = (array)$dados;
        }
        $filtros['regiao'] = $filtro;
        $filtro = array();

        $sql = "SELECT centro_custo.centro_custo FROM centro_custo";
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $filtro[] = (array)$dados;
        }
        $filtros['centro_custo'] = $filtro;
        $filtro = array();

        echo json_encode($filtros);
        
    }else if( isset( $_POST['opt_sql'] ) ){
        $_data = array();
        $sql = $_POST['opt_sql'];
        $source = $cn->executa_sql($sql);
        while( $dados = $cn->extrai($source) ){
            $_data[] = (array)$dados;
        }

        echo json_encode( $_data );
    }
    

?>