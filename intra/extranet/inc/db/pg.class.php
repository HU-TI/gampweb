<?
/*
	Classe para conexão em PostgreSQL, para utilizá-lo renomeie este arquivo
	para "db.class.php"
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
	
	function db($database_type="postgresql") {
		$this->type="postgresql";
		if (!function_exists("pg_pconnect")) {
			dl("pgsql.so");
		}
	}
	
	//----- executa uma expressão SQL
	function execute($strSQL) {
		pg_query($this->connect_id, $strSQL);
	}

	//----- envia begin ao servidor de dados
	function begin() {
		pg_query($this->connect_id, "BEGIN");
	}

	//----- envia commit ao servidor de dados
	function commit() {
		pg_query($this->connect_id, "COMMIT");
	}

	//----- envia rollback ao servidor de dados
	function rollback() {
		pg_query($this->connect_id, "ROLLBACK");
	}
	
	function open($database=DB_DATABASE, $host=DB_HOST, $user=DB_USER, $password=DB_PASSWORD) {
		// $host::="hostname:port-number"
		
		$connstr="dbname=".$database;
		if ($host) {
			list($host,$port)=split(":", $host);
			$connstr=$connstr." host=$host port=$port";
		}
		if ($user) {
			$connstr=$connstr." user=".$user;
		}
		if ($password) {
			$connstr=$connstr." password=".$password;
		}
		$this->connect_id=@pg_connect($connstr);
		return $this->connect_id;
	}
	
	function lock($table, $mode="write") {
		if ($mode="write") {
			$query=new query($this, "lock table $table");
			$result=$query->result;
		} else {
			$result=1;
		}
		return $result;
	}
	
	function unlock() {
		$query=new query($this, "commit");
		$result=$query->result;
		return $result;
	}
	
	function nextid($sequence) {
		$esequence=ereg_replace("'","''",$sequence);
		if (($query=new query($this, "select nextval('$esequence') as nextid") && $query->getrow())) {
			$nextid=$query->field("nextid");
		} else {
			if ($query->query($this, "create sequence $sequence") && $query->result) {
				$nextid=$this->nextid($sequence);
			} else {
				$nextid=0;
			}
		}
		return $nextid;
	}
	
	function error() {
		return pg_last_error($this->connect_id);
	}
	
	function close() {
		$query=new query($this, "commit");
		if ($this->query_id && is_array($this->query_id)) {
			while (list($key,$val)=each($this->query_id)) {
				@pg_free_result($val);
			}
		}
		$result=@pg_close($this->connect_id);
		return $result;
	}
	
	function addquery($query_id) {
		$this->query_id[]=$query_id;
	}
};

/*********************************** QUERY *********************************/

class query {
	var $result;
	var $row;
	var $curr_row;
	var $numrows;
	var $totalpages=0;
	
	function query(&$db, $query="", $pagina_inicial=0, $tamanho_pagina=0) {
		if ($this->result) {
			$this->free();
		}
		$this->result=@pg_query($db->connect_id, $query);
		$this->numrows = @pg_num_rows($this->result);
		
		if (($pagina_inicial+$tamanho_pagina) > 0) {
			$this->totalpages = ceil($this->numrows() / $tamanho_pagina);
			$query .= " limit $tamanho_pagina, " . ($pagina_inicial-1)*$tamanho_pagina;
		}
		$this->result=@pg_query($db->connect_id, $query);
		$db->addquery($this->result);
		$this->curr_row=0;
	}
	
	function getrow() {
		$this->row=@pg_fetch_array($this->result, $this->curr_row);
		$this->curr_row++;
		return $this->row;
	}
	
	function field($field) {
		return $this->row[$field];
	}
	
	function fieldname($fieldnum) {
		return pg_field_name( $this->result, $fieldnum );
	}
	
	function firstrow() {
		$this->curr_row=0;
		return $this->getrow();
	}
	
	function free() {
		return @pg_free_result($this->result);
	}
	
	//----- retorna a quantidade de registros
	function numrows() {
		return $this->numrows;
	}
	
	function totalpages() {
		return $this->totalpages;
	}
};
?>
