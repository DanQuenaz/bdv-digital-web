<?php
	class db{
    /*M�todo construtor do banco de dados*/
    public function __construct(){			
	
	}
     
    /*Evita que a classe seja clonada*/
    private function __clone(){}
     
    /*M�todo que destroi a conex�o com banco de dados e remove da mem�ria todas as vari�veis setadas*/
    public function __destruct() {
        $this->desconectar();
        foreach ($this as $key => $value) {
            unset($this->$key);
        }
    }
	// private $conexao = null;
    // private static $dbtype   = "mysql";
    // private static $host     = "localhost";
    // private static $port     = "65000";
    // private static $user     = "root";
	// private static $password = '%senha%bancoop3';
	// private static $db       = "base_bdv";

	private static $dbtype   = "mysql";
    private static $host     = "127.0.0.1";
    private static $port     = "";
    private static $user     = "root";
	private static $password = "";	
	private static $db       = "base_bdv";
     
    /*Metodos que trazem o conteudo da variavel desejada
    @return   $xxx = conteudo da variavel solicitada*/
    private function getDBType()  {return self::$dbtype;}
    private function getHost()    {return self::$host;}
    private function getPort()    {return self::$port;}
    private function getUser()    {return self::$user;}
    private function getPassword(){return self::$password;}
    private function getDB()      {return self::$db;}     
	/*para conectar ao banco de dados*/ 
    public function conectar(){
        try{
            $this->conexao = new PDO($this->getDBType().":host=".$this->getHost().";port=".$this->getPort().";dbname=".$this->getDB(), $this->getUser(), $this->getPassword());
			$this->conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch (PDOException $i){			
			Throw New Exception( "Erro na conex�o com o banco de dados: <code>" . $i->getMessage() . "</code>" );         
		}    
    }
	public function IniciaTransacao(){
		$this->conexao->beginTransaction();				
	}
	public function ConfirmaTransacao(){
		try{
			$this->conexao->commit();
		}catch(PDOException $i){
			self::__destruct();
			Throw New Exception("Erro ao confirmar a transa��o: <code>" . $i->getMessage() . "</code>"); 
		}
	}
	public function CancelaTransacao(){
		try{
			$this->conexao->rollBack();
		}catch(PDOException $i){			
			self::__destruct();
			Throw New PDOException("Erro ao cancelar transa��o: <code>" . $i->getMessage() . "</code>"); 
		}
	}
	public function UltimoId(){
		return $this->conexao->lastInsertId();
	}
/*M�todo select que retorna um VO ou um array de objetos*/
    public function executa_sql($sql, $parametros=null){
		try{			
			$query = $this->conexao->prepare($sql);
			if ( $parametros != null ){
				foreach ( $parametros as $key => &$value )
					$query->bindParam($key, $value);				
			}
			$query->execute();
			return $query;	
		}catch ( PDOException $i ){				
			switch ($i->errorInfo[1]) {
			case '1451'://erro de exclusao de registro com relacao com outra tabela
				Throw New Exception("N�o � poss�vel excluir. Esse registro possui rela��o com outra tabela"); 
				break;
			case '1062'://erro de registro duplicado
				throw new exception($i->errorInfo[1]);
				break;			
			default:
			   Throw New Exception("Erro na query: <code>" . $i->getMessage() . "</code> QUERY: ".$sql); 
			}
			self::__destruct();
			exit;
		}
    }
	public function extrai($source){
		return $source->fetch(PDO::FETCH_OBJ);
	}
	public function qtde_registros($source){
		return $source->rowCount();
	}
    private function desconectar(){
        $this->conexao = null;
    }
}
?>