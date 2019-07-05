<?
/*
	Classe para conexão em MySQL
*/

/*********************************************
 * Classes para acesso à camada de dados
 * por Marcelo Rezende (malvre@gmail.com)
 * atualizado em 14/11/2002 -> suporte a navegacao de registros
 *
 *
 * Classe......: db
 * Métodos.....: db("tipodb") construtor, *experimental, usar sem parâmetros
 *               open(banco, host, user, password)
 *	              lock(tabela, modo)
 *               unlock()
 *               error()
 *               close()
 *               execute(sql)
 *               begin()
 *               commit()
 *               rollback()
 *
 * Classe......: query
 * Métodos.....: query(db, sql, numero_pagina, tamanho_pagina) -> construtor
 *               getrow()
 *               field(campo)
 *               fieldname([numerodocampo] ou [nomedocampo])
 *               firstrow()
 *               free()
 *               numrows()
 *               totalpages()
 *				  
 **************************/
 
class db {
	var $connect_id;
	var $type;
	
	//----- construtor, parâmetro default é "mysql"
	function db($database_type="mysql") {
		$this->type="mysql";
	}
	
	//----- executa uma expressão SQL
	function execute($strSQL) {
		@mysql_query($strSQL, $this->connect_id);
		return @mysql_insert_id($this->connect_id);
	}
	
	//----- begin transaction
	function begin() {
		@mysql_query("BEGIN",$this->connect_id);
	}
	
	//----- commit transaction
	function commit() {
		@mysql_query("COMMIT",$this->connect_id);
	}
	
	//----- rollback transaction
	function rollback() {
		@mysql_query("ROLLBACK",$this->connect_id);
	}
	
	//----- abertura do banco de dados
	//----- configure a conexão conforme suas necessidades
	function open($database=DB_DATABASE, $host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD) {
		if (DB_PERSISTENT) {
			$this->connect_id=@mysql_pconnect($host, $user, $password);
		} else {
			$this->connect_id=@mysql_connect($host, $user, $password);
		}
		if ($this->connect_id) {
			$result=@mysql_select_db($database);
			if (!$result) {
				@mysql_close($this->connect_id);
				$this->connect_id=$result;
			}
		}
		return $this->connect_id;
	}
	
	//----- efetua lock na tabela
	function lock($table, $mode="write") {
		$query=new query($this, "lock tables $table $mode");
		$result=$query->result;
		return $result;
	}
	
	//----- efetua unlock nas tabelas em lock
	function unlock() {
		$query=new query($this, "unlock tables");
		$result=$query->result;
		return $result;
	}
	
	//----- retorna mensagem de erro
	function error($string_erro="") {
		//----- caso ocorra erro, envia mensagem
		if (@mysql_errno($this->connect_id)!=0) {
			@mail(SIS_EMAIL_RESPONSAVEL,"Erro " . date("d-m-Y"), mysql_errno($this->connect_id) . " - " . mysql_error($this->connect_id) . " - " . $string_erro);
		}
		return @mysql_errno($this->connect_id);
	}
	
	//----- retorna mensagem de erro
	function errorFull() {
    $erro = $this->error();
		 if ($erro == 1) $erroFull = "erro 001";
		 return $erroFull;
	}
	
	//----- encerra conexão e todos recorsets abertos
	function close() {
			if ($this->query_id && is_array($this->query_id)) {
				while (list($key,$val)=each($this->query_id)) {
					@mysql_free_result($val);
				}
			}
		if (DB_PERSISTENT) {
			$result=@mysql_close($this->connect_id);
			return $result;
		}
	}
	
	//----- gera pool de recordsets. método privado, não utilizar !!!
	function addquery($query_id) {
		$this->query_id[]=$query_id;
	}
	
};

class query {
	var $result;
	var $row;
	var $numrows;
	var $totalpages=0;
	
	//----- construtor, retorna recordset
	function query(&$db, $query="", $pagina_inicial=0, $tamanho_pagina=0) {
		if ($query) {
			if ($this->result) {
				$this->free();
			}
			$this->result = @mysql_query($query, $db->connect_id);
			$this->numrows = @mysql_num_rows($this->result);
			
			if (($pagina_inicial+$tamanho_pagina) > 0) {
				$this->totalpages = ceil($this->numrows() / $tamanho_pagina);
				$query .= " limit " . ($pagina_inicial-1)*$tamanho_pagina . ", $tamanho_pagina";
			}
			$this->result=@mysql_query($query, $db->connect_id);
			$db->addquery($this->result);
		}
	}
	
	function totalpages() {
		return $this->totalpages;
	}
	
	//----- retorna array com os campos e avança o registro
	function getrow() {
		if ($this->result) {
			$this->row=@mysql_fetch_array($this->result);
		} else {
			$this->row=0;
		}
		return $this->row;
	}
	
	//----- retorna o valor do campo
	function field($field) {
		if(get_magic_quotes_gpc()) {
			$result=stripslashes($this->row[$field]);
		} else {
		 	$result=$this->row[$field];
		}
		return $result;
	}
	
	//----- retorna o nome do campo
	function fieldname($fieldnum) {
		return @mysql_field_name( $this->result, $fieldnum );
	}
	
	//----- retorna primeira linha do recordset
	function firstrow() {
		$result=@mysql_data_seek($this->result,0);
		if ($result) {
			$result=$this->getrow();
		}
		return $this->row;
	}
	
	//----- fecha o recordset
	function free() {
		return @mysql_free_result($this->result);
	}
	
	//----- retorna a quantidade de registros
	function numrows() {
		return $this->numrows;
	}
}
?>