<?php
date_default_timezone_set('America/Sao_Paulo');

class service{
    
    private $cn;
    
    public function __construct($cn){
        $this->cn = $cn;
    }

    public function retornaMotoristas(){
        $row_s = array();//Array para armazenar resultados da consulta
        //Seleciona todos motoristas cadastrados
        $sql = "SELECT * FROM motorista";
		$source = $this->cn->executa_sql($sql);
		if ( $this->cn->qtde_registros($source) == 0 ){
			return "0";
		}else{
			while( $dados = $this->cn->extrai($source)){			
				$row_s[] = (array)$dados;	
			}
        }
        return json_encode($row_s);
    }

    public function insereBDV($bdvs){
        $check = 1;
        $this->cn->iniciaTransacao();	
        try{
            for($i=0; $i < count( $bdvs ); $i++){
                $motoristaNome = $bdvs[$i]->{'motoristaNome'};
                $motoristaID = $bdvs[$i]->{'motoristaID'};
                $frota_veiculo = $bdvs[$i]->{'frota_veiculo'};
                $hora_inicial = $bdvs[$i]->{'hora_inicial'};
                $hora_final = $bdvs[$i]->{'hora_final'};
                $km_inicial = $bdvs[$i]->{'km_inicial'};
                $km_final = $bdvs[$i]->{'km_final'};
                $km_total = $bdvs[$i]->{'km_total'};
                $km_calculado = $bdvs[$i]->{'km_calculado'};
                $km_rodovia = $bdvs[$i]->{'km_rodovia'};
                $km_cidade = $bdvs[$i]->{'km_cidade'};
                $velocidade_media = $bdvs[$i]->{'velocidade_media'};
                $servico = $bdvs[$i]->{'servico'};
                $reserva = $bdvs[$i]->{'reserva'};
                $placa_reserva = $bdvs[$i]->{'placa_reserva'};
                $centro_custo = $bdvs[$i]->{'centro_custo'};
                $assinaturas = $bdvs[$i]->{'assinaturas'};
                $rota = $bdvs[$i]->{'rota'};

                $sql = "INSERT INTO `bdv` (`bdvID`, `motoristaNome`, `motoristaID`, `frota_veiculo`, `hora_inicial`, `hora_final`, `km_inicial`, `km_final`, `km_total`, `km_calculado`, `km_rodovia`, `km_cidade`, `velocidade_media`, `servico`, `reserva`, `placa_reserva`, `centro_custo`) VALUES (NULL, :motoristaNome, :motoristaID, :frota_veiculo, :hora_inicial, :hora_final, :km_inicial, :km_final, :km_total, :km_calculado, :km_rodovia, :km_cidade, :velocidade_media, :servico, :reserva, :placa_reserva, :centro_custo)";
                $parametros = array(':motoristaNome'=>$motoristaNome,
                                    ':motoristaID'=>$motoristaID,
                                    ':frota_veiculo'=>$frota_veiculo,
                                    ':hora_inicial'=>$hora_inicial,
                                    ':hora_final'=>$hora_final,
                                    ':km_inicial'=>$km_inicial,
                                    ':km_final'=>$km_final,
                                    ':km_total'=>$km_total,
                                    ':km_calculado'=>$km_calculado,
                                    ':km_rodovia'=>$km_rodovia,
                                    ':km_cidade'=>$km_cidade,
                                    ':velocidade_media'=>$velocidade_media,
                                    ':servico'=>$servico,
                                    ':reserva'=>$reserva,
                                    ':placa_reserva'=>$placa_reserva,
                                    ':centro_custo'=>$centro_custo
                                    );
                $this->cn->executa_sql($sql, $parametros);
                $this->insereAssinaturas($assinaturas);
                $this->insereRota($rota);
            }
            $this->cn->ConfirmaTransacao();
            $this->cn = null;
            return "1#";
        }catch(PDOException $e){
            return "0#*".$e->getMessage();
            $this->cn->CancelaTransacao();
            $this->cn = null;
            exit;
        }
    }

    public function checaSenhaADM($senha){
        $sql = "SELECT * FROM senha_adm";
		$source = $this->cn->executa_sql($sql);
		if ( $this->cn->qtde_registros($source) == 0 ){
			return "NOT_OK#";
		}else{
			if( $dados = $this->cn->extrai($source)){	
				//var_dump($dados);		
				if($senha == $dados->senha){
					return "OK#";
				}else{
					return "NOT_OK#";
				}
			}else{
				return "NOT_OK#";
			}
		}
    }

    public function alteraSenhaMotorista($_dados){
        $matricula = $_dados->{'matricula'};
        $nova_senha = $_dados->{'nova_senha'};
        //return $matricula." - ".$nova_senha;
        $this->cn->iniciaTransacao();
        try{
            $sql2 = "UPDATE motorista SET senha=:senha WHERE matricula=:matricula";
            $parametros = array(
                ':senha'=>$nova_senha,
                ':matricula'=>$matricula
            );
            $source = $this->cn->executa_sql($sql2, $parametros);
            $this->cn->ConfirmaTransacao();
            $this->cn = null;
            return "OK#";
        }catch(PDOException $e){
            return "0#*".$e->getMessage();
            $this->cn->CancelaTransacao();
            $this->cn = null;
            exit;
        }
    }

    private function insereAssinaturas($assinaturas){
        $sql1 = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'base_bdv' AND TABLE_NAME = 'bdv';";
        $source = $this->cn->executa_sql($sql1);
        $dados = $this->cn->extrai($source);
        $id = $dados->{'AUTO_INCREMENT'} - 1;
    
        for($i=0; $i<count($assinaturas); $i++){
            $nome_passageiro = $assinaturas[$i]->{'nome_passageiro'};
            $matricula_passageiro = $assinaturas[$i]->{'matricula_passageiro'};
            $observacao = $assinaturas[$i]->{'observacao'};
            $avaliacao = $assinaturas[$i]->{'avaliacao'};
            $bdvID = $id;
            
            $sql2 = "INSERT INTO `assinatura` (`assinaturaID`, `nome_passageiro`, `matricula_passageiro`, `observacao`, `avaliacao`, `bdvID`) VALUES (NULL, :nome_passageiro, :matricula_passageiro, :observacao, :avaliacao, :bdvID)";
                $parametros = array(':nome_passageiro'=>$nome_passageiro,
                                    ':matricula_passageiro'=>$matricula_passageiro,
                                    ':observacao'=>$observacao,
                                    ':avaliacao'=>$avaliacao,
                                    ':bdvID'=>$bdvID
                                    );
                   $this->cn->executa_sql($sql2, $parametros);
        }
    }
    
    private function insereRota($rota){
        $sql1 = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'base_bdv' AND TABLE_NAME = 'bdv';";
        $source = $this->cn->executa_sql($sql1);
        $dados = $this->cn->extrai($source);
        $id = $dados->{'AUTO_INCREMENT'} - 1;
    
        for($i=0; $i<count($rota); $i++){
            $latitude = $rota[$i]->{'latitude'};
            $longitude = $rota[$i]->{'longitude'};
            $altitude = $rota[$i]->{'altitude'};
            $comportamento = $rota[$i]->{'comportamento'};
            $velocidade = $rota[$i]->{'velocidade'};
            $provedor = $rota[$i]->{'provedor'};
            $acuracia = $rota[$i]->{'acuracia'};
            $hora = $rota[$i]->{'hora'};
            $cont = $rota[$i]->{'cont'};
            $bdvID = $id;
            
            $sql2 = "INSERT INTO `rota` (`rotaID`, `latitude`, `longitude`, `altitude`, `comportamento`, `velocidade`, `provedor`, `acuracia`, `hora`, `cont`, `bdvID`) VALUES (NULL, :latitude, :longitude, :altitude, :comportamento, :velocidade, :provedor, :acuracia, :hora, :cont, :bdvID)";
                $parametros = array(':latitude'=>$latitude,
                                    ':longitude'=>$longitude,
                                    ':altitude'=>$altitude,
                                    ':comportamento'=>$comportamento,
                                    ':velocidade'=>$velocidade,
                                    ':provedor'=>$provedor,
                                    ':acuracia'=>$acuracia,
                                    ':hora'=>$hora,
                                    ':cont'=>$cont,
                                    ':bdvID'=>$bdvID
                                    );
                $this->cn->executa_sql($sql2, $parametros);
        }
    }

    public function insereCheckList($_dados){
        $check = 1;
        $this->cn->iniciaTransacao();	
        try{
            for($i=0; $i < count( $_dados ); $i++){
                $veiculoCartela = $_dados[$i]->{'veiculoCartela'};
                $motoristaNome = $_dados[$i]->{'motoristaNome'};
                $motoristaMatricula = $_dados[$i]->{'motoristaMatricula'};
                $dia_hora = $_dados[$i]->{'dia_hora'};
                $item1 = $_dados[$i]->{'item1'};
                $item2 = $_dados[$i]->{'item2'};
                $item3 = $_dados[$i]->{'item3'};
                $item4 = $_dados[$i]->{'item4'};
                $item5 = $_dados[$i]->{'item5'};
                $item6 = $_dados[$i]->{'item6'};
                $item7 = $_dados[$i]->{'item7'};
                $item8 = $_dados[$i]->{'item8'};
                $item9 = $_dados[$i]->{'item9'};
                $item10 = $_dados[$i]->{'item10'};
                $item11 = $_dados[$i]->{'item11'};
                $item12 = $_dados[$i]->{'item12'};
                $item13 = $_dados[$i]->{'item13'};
                $item14 = $_dados[$i]->{'item14'};
                $item15 = $_dados[$i]->{'item15'};
                $item16 = $_dados[$i]->{'item16'};
                $item17 = $_dados[$i]->{'item17'};
                $item18 = $_dados[$i]->{'item18'};
                $item19 = $_dados[$i]->{'item19'};
                $item20 = $_dados[$i]->{'item20'};
                $item21 = $_dados[$i]->{'item21'};
                $item22 = $_dados[$i]->{'item22'};
                $item23 = $_dados[$i]->{'item23'};
                $item24 = $_dados[$i]->{'item24'};
                $item25 = $_dados[$i]->{'item25'};
                $item26 = $_dados[$i]->{'item26'};
                $item27 = $_dados[$i]->{'item27'};
                $item28 = $_dados[$i]->{'item28'};
                $item29 = $_dados[$i]->{'item29'};
                $observacoes = $_dados[$i]->{'observacoes'};
                $sql = "INSERT INTO `checkin_frota` (`checkinID`,`veiculoCartela`, `motorista_nome`, `motorista_matricula`,`dia_hora`, `item1`, `item2`, `item3`, `item4`, `item5`, `item6`, `item7`, `item8`, `item9`, `item10`, `item11`, `item12`, `item13`, `item14`, `item15`, `item16`, `item17`, `item18`, `item19`, `item20`, `item21`, `item22`, `item23`, `item24`, `item25`, `item26`, `item27`, `item28`, `item29`, `observacoes`) VALUES (NULL, :veiculoCartela, :motoristaNome, :motoristaMatricula, :dia_hora, :item1, :item2, :item3, :item4, :item5, :item6, :item7, :item8, :item9, :item10, :item11, :item12, :item13, :item14, :item15, :item16, :item17, :item18, :item19, :item20, :item21, :item22, :item23, :item24, :item25, :item26, :item27, :item28, :item29, :observacoes)";
                $parametros = array(':veiculoCartela'=>$veiculoCartela,
                                    ':motoristaNome'=>$motoristaNome,
                                    ':motoristaMatricula'=>$motoristaMatricula,
                                    ':dia_hora'=>$dia_hora,
                                    ':item1'=>$item1,
                                    ':item2'=>$item2,
                                    ':item3'=>$item3,
                                    ':item4'=>$item4,
                                    ':item5'=>$item5,
                                    ':item6'=>$item6,
                                    ':item7'=>$item7,
                                    ':item8'=>$item8,
                                    ':item9'=>$item9,
                                    ':item10'=>$item10,
                                    ':item11'=>$item11,
                                    ':item12'=>$item12,
                                    ':item13'=>$item13,
                                    ':item14'=>$item14,
                                    ':item15'=>$item15,
                                    ':item16'=>$item16,
                                    ':item17'=>$item17,
                                    ':item18'=>$item18,
                                    ':item19'=>$item19,
                                    ':item20'=>$item20,
                                    ':item21'=>$item21,
                                    ':item22'=>$item22,
                                    ':item23'=>$item23,
                                    ':item24'=>$item24,
                                    ':item25'=>$item25,
                                    ':item26'=>$item26,
                                    ':item27'=>$item27,
                                    ':item28'=>$item28,
                                    ':item29'=>$item29,
                                    ':observacoes'=>$observacoes
                                    );
                $this->cn->executa_sql($sql, $parametros);
            }          
            $this->cn->ConfirmaTransacao();
            $this->cn = null;
            return "1#";
        }catch(PDOException $e){
            return "0#*".$e->getMessage();
            $this->cn->CancelaTransacao();
            $this->cn = null;
            exit;
        } 
    }

    public function insereHoraExtra($_dados){
        $check = 1;
        $this->cn->iniciaTransacao();	
        try{
            for($i=0; $i < count( $_dados ); $i++){
                $motorista_matricula = $_dados[$i]->{'motorista_matricula'};
                $hora_login = $_dados[$i]->{'hora_login'};
                $hora_logout = $_dados[$i]->{'hora_logout'};
                $hora_primeira_rota = $_dados[$i]->{'hora_primeira_rota'};
                $hora_ultima_rota = $_dados[$i]->{'hora_ultima_rota'};
                $total_hora_logado = $_dados[$i]->{'total_hora_logado'};
                $total_hora_rota = $_dados[$i]->{'total_hora_rota'};
                $dia_semana = $_dados[$i]->{'dia_semana'};

                $sql = "INSERT INTO `hora_extra` (`heID`,`motorista_matricula`, `hora_login`, `hora_logout`,`hora_primeira_rota`, `hora_ultima_rota`, `total_hora_logado`, `total_hora_rota`, `dia_semana`) VALUES (NULL, :motorista_matricula, :hora_login, :hora_logout, :hora_primeira_rota, :hora_ultima_rota, :total_hora_logado, :total_hora_rota, :dia_semana)";
                $parametros = array(':motorista_matricula'=>$motorista_matricula,
                                    ':hora_login'=>$hora_login,
                                    ':hora_logout'=>$hora_logout,
                                    ':hora_primeira_rota'=>$hora_primeira_rota,
                                    ':hora_ultima_rota'=>$hora_ultima_rota,
                                    ':total_hora_logado'=>$total_hora_logado,
                                    ':total_hora_rota'=>$total_hora_rota,
                                    ':dia_semana'=>$dia_semana
                                    );
                $this->cn->executa_sql($sql, $parametros);
            }         
            $this->cn->ConfirmaTransacao();
            $this->cn = null;
            return "1#";
        }catch(PDOException $e){
            return "0#*".$e->getMessage();
            $this->cn->CancelaTransacao();
            $this->cn = null;
            exit;
        } 
    }

    public function insereCustosMotorista($_dados){
        $check = 1;
        $this->cn->iniciaTransacao();	
        try{
            for($i=0; $i < count( $_dados ); $i++){
                $descricao = $_dados[$i]->{'descricao'};
                $data_custo = $_dados[$i]->{'data_custo'};
                $valor = $_dados[$i]->{'valor'};
                $motorista_matricula = $_dados[$i]->{'motorista_matricula'};

                $sql = "INSERT INTO `custos_motorista` (`cmID`, `descricao`, `data_custo`, `valor`, `motorista_matricula`) VALUES (NULL, :descricao, :data_custo, :valor, :motorista_matricula)";
                $parametros = array(':descricao'=>$descricao,
                                    ':data_custo'=>$data_custo,
                                    ':valor'=>$valor,
                                    ':motorista_matricula'=>$motorista_matricula
                                    );
                $this->cn->executa_sql($sql, $parametros);
            }
            $this->cn->ConfirmaTransacao();
            $this->cn = null;
            return "1#";
        }catch(PDOException $e){
            return "0#*".$e->getMessage();
            $this->cn->CancelaTransacao();
            $this->cn = null;
            exit;
        } 
    }
}

?>