<?
/*********************************************
 * Classes para acesso à camada de dados
 * por Marcelo Rezende (marcelo_rezende@yahoo.com)
 * atualizado em 14/11/2002 -> suporte a navegacao de registros
 *
 *
 * Classe......: db
 * Métodos.....: db("tipodb") construtor, *experimental, usar sem parâmetros
 *               open(banco, host, user, password)
 *	             lock(tabela, modo)
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
	
	//----- construtor, parâmetro default é "mssql"
	function db($database_type="mssql") {
		$this->type="mssql";
	}
	
	//----- executa uma expressão SQL
	function execute2($strSQL) {
		mssql_query($strSQL, $this->connect_id);
		return mssql_insert_id($this->connect_id);
	}

	//----- executa uma expressão SQL
	function execute($strSQL) {
		mssql_query($strSQL, $this->connect_id);
		$rs = @mssql_query("SELECT @@IDENTITY AS 'Identity'",$this->connect_id);
		$row = @mssql_fetch_array($rs);
		return $row["Identity"];
	}
	
	//----- begin transaction
	function begin() {
		mssql_query("BEGIN",$this->connect_id);
	}
	
	//----- commit transaction
	function commit() {
		mssql_query("COMMIT",$this->connect_id);
	}
	
	//----- rollback transaction
	function rollback() {
		mssql_query("ROLLBACK",$this->connect_id);
	}
	
	//----- abertura do banco de dados
	function open($database=DB_DATABASE, $host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD) {
		$this->connect_id=mssql_pconnect($host, $user, $password);
		if ($this->connect_id) {
			$result=mssql_select_db($database);
			if (!$result) {
				mssql_close($this->connect_id);

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
		//----- falta implementar o envio da página que está gerando o erro
		if (mssql_errno($this->connect_id)!=0) {
			mail(SIS_EMAIL_RESPONSAVEL,"Erro " . date("d-m-Y"), mssql_errno($this->connect_id) . " - " . mssql_error($this->connect_id) . " - " . $string_erro);
		}
		return mssql_errno($this->connect_id);
	}
	
	//----- encerra conexão e todos recorsets abertos
	function close() {
		if ($this->query_id && is_array($this->query_id)) {
			while (list($key,$val)=each($this->query_id)) {
				@mssql_free_result($val);
			}
		}
		$result=@mssql_close($this->connect_id);
		return $result;
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
            $this->result = @mssql_query($query, $db->connect_id);
            $this->numrows = @mssql_num_rows($this->result);

            $db->addquery($this->result);
        }
    }
	
	function totalpages() {
    	return $this->totalpages;
    }
    
    //----- retorna array com os campos e avança o registro
	function getrow() {
		if ($this->result) {
			$this->row=mssql_fetch_array($this->result);
		} else {
			$this->row=0;
		}
		return $this->row;
	}
	
	//----- retorna o valor do campo
	function field($field) {
		$result=$this->row[$field];
		return $result;
	}
	
	//----- retorna o nome do campo
	function fieldname($fieldnum) {
		return mssql_field_name( $this->result, $fieldnum );
	}
	
	//----- retorna primeira linha do recordset
	function firstrow() {
		$result=@mssql_data_seek($this->result,0);
		if ($result) {
			$result=$this->getrow();
		}
		return $this->row;
	}
	
	//----- fecha o recordset
	function free() {
		return @mssql_free_result($this->result);
	}
	
	//----- retorna a quantidade de registros
	function numrows() {
		return $this->numrows;
	}
}
?>
