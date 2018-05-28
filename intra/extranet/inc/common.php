<?php
/***
 * phpFramework
 * desenvolvido por Marcelo Rezende
 * malvre@gmail.com
 */
session_start();
require 'PHPMailer/PHPMailerAutoload.php';
include("../inc/config.inc.php");
include(DB_DEFAULT);

/*****************************************************************************************************
	Classe para montagem de expressões SQL de atualização
	O método getValue deve ser adaptado conforme o banco de dados utilizado.
	No futuro esta classe será mais generalizada
*/
class UpdateSQL {
	var $action;
	var $table;
	
	var $keyField;
	var $keyValue;
	var $keyType;
	
	var $updateFields;
	var $updateValues;
	var $updateTypes;
	
	/*
		Construtor
		theAction : INSERT, UPDATE, DELETE
		theTable : nome da tabela
	*/
	function UpdateSQL($theAction="", $theTable="") {
		$this->action = strtoupper($theAction);
		$this->table = $theTable;
	}
	
	/*
		Define a chave
		theField : nome do campo
		theValue : valor do campo
		theType : tipo do campo (Number, String, Date)
	*/
	function setKey($theField, $theValue, $theType) {
		$this->keyField = $theField;
		$this->keyValue = $theValue;
		$this->keyType = $theType;
	}
	
	/*
		Adiciona um campo na expressão SQL
		theField : nome do campo
		theValue : valor do campo
		theType : tipo do campo (Number, String, Date)
	*/
	function addField($theField, $theValue, $theType) {
		$this->updateFields[] = $theField;
		$this->updateValues[] = $theValue;
		$this->updateTypes[] = $theType;
	}
	
	/*
		Define a ação da expressão SQL
		theAction : INSERT, UPDATE, DELETE
	*/
	function setAction($theAction) {
		$this->action = strtoupper($theAction);
	}
	
	/*
		Define a tabela que vai sofrer atualização
		theTable : nome da tabela
	*/
	function setTable($theTable) {
		$this->table = $theTable;
	}
	
	/*
		Monta a expressão SQL e retorna como string
	*/
	function getSQL() {
		$sql = "";
		// inclusão
		if ($this->action=="INSERT") {
			$sql .= "INSERT INTO " . $this->table . " (";
			$fieldlist = "";
			$valuelist = "";
			for ($i=0; $i<sizeof($this->updateFields); $i++) {
				$fieldlist .= $this->updateFields[$i] . ", ";
				$valuelist .= $this->getValue($this->updateValues[$i], $this->updateTypes[$i]) . ", ";
			}
			$fieldlist = substr($fieldlist,0,-2);
			$valuelist = substr($valuelist,0,-2);
			$sql .= $fieldlist . ") VALUES (" . $valuelist . ")";
		}

		// alteração
		if ($this->action=="UPDATE") {
			$sql .= "UPDATE " . $this->table . " SET ";
			$updatelist = "";
			for ($i=0; $i<sizeof($this->updateFields); $i++) {
				$updatelist .= $this->updateFields[$i] . "=" .
				               $this->getValue($this->updateValues[$i], $this->updateTypes[$i]) . ", ";
			}
			$updatelist = substr($updatelist,0,-2);
			$sql .= $updatelist . " WHERE " . $this->keyField . "=" . $this->getValue($this->keyValue, $this->keyType);
		}

		// exclusão
		if ($this->action=="DELETE") {
			$sql .= "DELETE FROM " . $this->table . " WHERE " . $this->keyField . "=" . $this->getValue($this->keyValue, $this->keyType);
		}
		
		return $sql;
	}
	
	/*
		Formata o valor conforme o tipo
		value : valor do campo
		type : tipo do campo (Number, String, Date) 
	*/
	function getValue($value, $type) {
		if (!strlen($value)) {
			return "NULL";
		} else {
			if ($type == "Number") {
				return str_replace (",", ".", doubleval($value));
			} else {
				if (get_magic_quotes_gpc() == 0) {
					$value = str_replace("'","''",$value);
					$value = str_replace("\\","\\\\",$value);
				} else {
					$value = str_replace("\\'","''",$value);
					$value = str_replace("\\\"","\"",$value);
				}
				return "'" . $value . "'";
			}
		}
	}
		/*
		preenche campos de controle
		incExc : INSERT ou UPDATE
		$dth = data e hora no formato do banco, ou o comando php dbnow()
	*/
 function camposControle($incExc = "INSERT",$dth){
   if ($incExc == "INSERT") {
		   $this->addField("ctr_dth_inc",    $dth,                        "Datetime");
			 $this->addField("ctr_usu_inc",    getSession("sis_usercode"),  "Number");
			 $this->addField("ctr_ip_inc",     $_SERVER['REMOTE_ADDR'],     "String");
		}
		else {
		   $this->addField("ctr_dth_atu",    $dth,                        "Datetime");
		   $this->addField("ctr_usu_atu",    getSession("sis_usercode"),  "Number");
		   $this->addField("ctr_ip_atu",     $_SERVER['REMOTE_ADDR'],     "String");
	  }
 }
}	
/********************************************************************
	Classe para criação de formulários
*/
class Form_Portal {
	var $name;
	var $action;
	var $method;
	var $target;
	var $width;
	var $blockFields;
	var $blockHidden;
	var $focus;
	var $upload;
	var $labelWidth;
	var $dataWidth;

	// define o tipo de documento
	function setUpload($fazUpload=false) {
		$this->upload = $fazUpload;
	}
	
	// define a largura da coluna label
	function setLabelWidth($valor) {
		$this->labelWidth = $valor;
	}
	
	// define a largura da coluna data
	function setDataWidth($valor) {
		$this->dataWidth = $valor;
	}
	
	// define o nome do formulário
	function setName($umNome) {
		$this->name = $umNome;
	}
	
	// define a ação do formulário
	function setAction($umaAcao) {
		$this->action = $umaAcao;
	}
	
	// define o método do formulário
	function setMethod($umMetodo) {
		$this->method = $umMetodo;
	}
	
	// define o target do formulário
	function setTarget($umTarget) {
		$this->target = $umTarget;
	}
	
	// define se campos terão highligth
	function setFocus($focus) {
		$this->focus = $focus;
	}
	
	// define a largura do formulário
	function setWidth($largura) {
		$this->width = $largura;
	}
	
	// construtor
	// $name : identificador do formulário
	// $action : action do formulário
	// $method : método a ser utilizado POST ou GET
	// $target : frame em que o action será executado
	// $width : largura do formulário
	// $focus : mecanismo de foco destacado, true ou false
	function Form($name="frm", $action="", $method="POST",  $width="100%", $focus=false) {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;		
		$this->width = $width;
		$this->blockFields = "";
		$this->blockHidden = "";
		$this->focus = $focus;
		$this->labelWidth = "30%";
		$this->dataWidth = "70%";
	}
	
	// adiciona campo hidden ao formulário
	// $varName : nome do campo
	// $varValue : valor do campo
	function addHidden($varName, $varValue) {
		$this->blockHidden .= "<input type='hidden' name='".$varName."' value='".$varValue."'>\n";
	}
	
	// adiciona campo ao formulário
	// $label : título do campo
	// $field : expressão html que define o campo
	function addField($label="", $field) {
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td width='".$this->labelWidth."' class='LabelTD' nowrap><font class='LabelFONT'>".$label."</font></td>";
		$this->blockFields .= "<td width='".$this->dataWidth."' class='DataTD'><font class='DataFONT'>".$field."</font></td>";
		$this->blockFields .= "</tr>\n";
	}
	
	// adiciona divisória ao formulário
	// $text : expressão que será mostrada dentro da quebra
	// $style : usar estilo predefinido? true ou false
	function addBreak($text="", $style=true) {
		$this->blockFields .= "<tr>";
		if ($style) {
			$this->blockFields .= "<td class='RecordSeparatorTD' colspan='2'><font class='RecordSeparatorFONT'>".$text."</font></td>";
		} else {
			$this->blockFields .= "<td colspan='2'>".$text."</td>";
		}
		$this->blockFields .= "</tr>\n";
	}
	
	// retorna bloco HTML com o formulário montado
	function writeHTML() {
		$out = "";
		$out .= "<table border='0' cellpadding='1' cellspacing='0' align='center' width='".$this->width."'>\n";
		$out .= "<tr><td>";
		
		$enctype = "";
		if ($this->upload) $enctype = "enctype='multipart/form-data'";
		
		if ($this->focus) {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."' onKeyUp='highlight(event)' onClick='highlight(event)'>\n";
		} else {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."'>\n";
		}
		$out .= $this->blockHidden;
		$out .= "<table class='FormTABLE' cellspacing=0>\n";
		$out .= $this->blockFields;
		$out .= "</table>\n";
		$out .= "</form>\n";
		$out .= "</td></tr></table>\n";
		return $out;
	}
}

/********************************************************************
	Classe para criação de formulários
*/
class Form {
	var $name;
	var $action;
	var $method;
	var $target;
	var $width;
	var $blockFields;
	var $blockHidden;
	var $focus;
	var $upload;
	var $labelWidth;
	var $dataWidth;

	// define o tipo de documento
	function setUpload($fazUpload=false) {
		$this->upload = $fazUpload;
	}
	
	// define a largura da coluna label
	function setLabelWidth($valor) {
		$this->labelWidth = $valor;
	}
	
	// define a largura da coluna data
	function setDataWidth($valor) {
		$this->dataWidth = $valor;
	}
	
	// define o nome do formulário
	function setName($umNome) {
		$this->name = $umNome;
	}
	
	// define a ação do formulário
	function setAction($umaAcao) {
		$this->action = $umaAcao;
	}
	
	// define o método do formulário
	function setMethod($umMetodo) {
		$this->method = $umMetodo;
	}
	
	// define o target do formulário
	function setTarget($umTarget) {
		$this->target = $umTarget;
	}
	
	// define se campos terão highligth
	function setFocus($focus) {
		$this->focus = $focus;
	}
	
	// define a largura do formulário
	function setWidth($largura) {
		$this->width = $largura;
	}
	
	// construtor
	// $name : identificador do formulário
	// $action : action do formulário
	// $method : método a ser utilizado POST ou GET
	// $target : frame em que o action será executado
	// $width : largura do formulário
	// $focus : mecanismo de foco destacado, true ou false
	function Form($name="frm", $action="", $method="POST", $target="controle", $width="100%", $focus=false) {
		$this->name = $name;
		$this->action = $action;
		$this->method = $method;
		$this->target = $target;
		$this->width = $width;
		$this->blockFields = "";
		$this->blockHidden = "";
		$this->focus = $focus;
		$this->labelWidth = "30%";
		$this->dataWidth = "70%";
	}
	
	// adiciona campo hidden ao formulário
	// $varName : nome do campo
	// $varValue : valor do campo
	function addHidden($varName, $varValue) {
		$this->blockHidden .= "<input type='hidden' name='".$varName."' value='".$varValue."'>\n";
	}
	
	// adiciona campo ao formulário
	// $label : título do campo
	// $field : expressão html que define o campo
	function addField($label="", $field) {
		$this->blockFields .= "<tr>";
		$this->blockFields .= "<td width='".$this->labelWidth."' class='LabelTD' nowrap><font class='LabelFONT'>".$label."</font></td>";
		$this->blockFields .= "<td width='".$this->dataWidth."' class='DataTD'><font class='DataFONT'>".$field."</font></td>";
		$this->blockFields .= "</tr>\n";
	}
	
	// adiciona divisória ao formulário
	// $text : expressão que será mostrada dentro da quebra
	// $style : usar estilo predefinido? true ou false
	function addBreak($text="", $style=true) {
		$this->blockFields .= "<tr>";
		if ($style) {
			$this->blockFields .= "<td class='RecordSeparatorTD' colspan='2'><font class='RecordSeparatorFONT'>".$text."</font></td>";
		} else {
			$this->blockFields .= "<td colspan='2'>".$text."</td>";
		}
		$this->blockFields .= "</tr>\n";
	}
	
	// retorna bloco HTML com o formulário montado
	function writeHTML() {
		$out = "";
		$out .= "<table border='0' cellpadding='1' cellspacing='0' align='center' width='".$this->width."'>\n";
		$out .= "<tr><td>";
		
		$enctype = "";
		if ($this->upload) $enctype = "enctype='multipart/form-data'";
		
		if ($this->focus) {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."' target='".$this->target."' onKeyUp='highlight(event)' onClick='highlight(event)'>\n";
		} else {
			$out .= "<form name='".$this->name."' id='".$this->name."' ".$enctype." action='".$this->action."' method='".$this->method."' target='".$this->target."'>\n";
		}
		$out .= $this->blockHidden;
		$out .= "<table class='FormTABLE' cellspacing=0>\n";
		$out .= $this->blockFields;
		$out .= "</table>\n";
		$out .= "</form>\n";
		$out .= "</td></tr></table>\n";
		return $out;
	}
}

/*****************************************************************************************************
 Classe para gerar tabelas
*/
class Table {
	var $block;
	var $title;
	var $width;
	var $row;
	var $columns;
	var $currcol;
	var $style;
	var $alternate = false;
	var $tableAlign;
	
	// Construtor
	// $title : título da tabela
	// $width : largura da tabela
	// $columns : quantidade de colunas na tabela
	// $style : usar estilo predefinido? true ou false
	function Table($title="", $width="100%", $columns, $style=true) {
		$this->title = $title;
		$this->width = $width;
		$this->columns = $columns;
		$this->currcol = 1;
		$this->style = $style;
		$this->tableAlign = "L";
	}
	
	// agrupa células e adiciona na linha
	function addRow() {
		$this->block .= "<tr>".$this->row."</tr>\n";
		$this->row = "";
		$this->currcol = 1;
		$this->alternate = !$this->alternate;
	}
	
	// cria célula
	// $data : conteúdo dentro da célula
	// $align : alinhamento (L, C, R)
	function addData($data="&nbsp", $align="L") {
		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";
		if ($this->style) {
			$st = $this->alternate?"AlternateDataTD":"DataTD";
			$this->row .= "<td class='$st' $al><font class='DataFONT'>".$data."</font></td>";
		} else {
			$this->row .= "<td $al>".$data."</td>";
		}
	}
	
	// cria título da coluna
	// $title : título da coluna
	// $ord : ordenar? true ou false
	// $width : largura da coluna
	// $align : alinhamento (L, C, R)
	function addColumnHeader($title="&nbsp;", $ord=false, $width="1", $align="L") {
		global $form_sorting;
		$cs = $this->currcol;

		$align = strtoupper($align);
		if ($align=="L") $al = "align=left";
		if ($align=="C") $al = "align=center";
		if ($align=="R") $al = "align=right";

		$this->row .= "<td class='ColumnTD' width='".$width."' $al>";
		if ($ord) {
			$this->row .= "<a title='Ordenar por $title' class='ColumnFontLink' href='".$_SERVER['PHP_SELF']."?Sorting=$cs&Sorted=$form_sorting'>".$title."</a>";
		} else {
			$this->row .= "<font class='ColumnFont'>".$title."</font>";
		}
		$this->row .= "</td>";
		$this->alternate = true;
		$this->currcol++;
	}
	
	// adiciona linha divisória na tabela
	// $title : expressão html que será exibida na quebra
	function addBreak($title="&nbsp", $style=true) {
		if (!$style) {
			$this->row .= "<td colspan='".$this->columns."'>".$title."</td>";
		} else {
			$this->row .= "<td class='RecordSeparatorTD' colspan='".$this->columns."'><font class='RecordSeparatorFONT'>".$title."</font></td>";
		}
		$this->addRow();
		$this->alternate = false;
	}
	
	// define o alinhamento da tabela
	function setTableAlign($tableAlign) {
		$this->tableAlign = strtoupper($tableAlign);
	}
	
	// retorna o bloco HTML com a tabela montada
	function writeHTML() {
		if ($this->tableAlign=="L") $ta = "<div align='left'>";
		if ($this->tableAlign=="C") $ta = "<div align='center'>";
		if ($this->tableAlign=="R") $ta = "<div align='right'>";
		$out .= "$ta<table border=0 cellspacing=0 cellpadding=1 width='".$this->width."'><tr><td vAlign='top' align='center'>";
		if ($this->style) {
			$out .= "<table class='FormTABLE' cellspacing=0>";
		} else {
			$out .= "<table border='0'>";
		}
		if ($this->title != "") {
			$out .= "<tr>";
			$out .= "<td class='FormHeaderTD' colspan='".$this->columns."'>";
			$out .= "<font class='FormHeaderFONT'>".$this->title."</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= $this->block;
		$out .= "</table>";
		$out .= "</td></tr></table></div>";
		return $out;
	}
}

/*****************************************************************************************************
	Classe pra gerar caixas de conteúdo
*/
class Box {
	var $title;
	var $width;
	var $content;
	
	// Construtor
	// $title : título do box
	// $width : largura do box
	function Box($title="", $width="100%") {
		$this->title = $title;
		$this->width = $width;
	}
	
	// adiciona conteúdo ao box
	// $texto : expressão html que será adicionada ao box
	function addContent($texto="") {
		$this->content .= $texto;
	}
	
	// retorna bloco HTML com o box montado
	function writeHTML() {
		$out = "";
		$out .= "<table border=0 cellspacing=0 cellpadding=0 width='".$this->width."'><tr><td vAlign='top'>";
		$out .= "<table class='FormTABLE'>";
		if ($this->title!="") {
			$out .= "<tr>";
			$out .= "<td class='FormHeaderTD'>";
			$out .= "<font class='FormHeaderFONT'>".$this->title."</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= "<tr>";
		$out .= "<td class='DataTD'>";
		$out .= "<font class='DataFONT'>";
		$out .= $this->content;
		$out .= "</font>";
		$out .= "</td>";
		$out .= "</tr>";
		$out .= "</table>";
		$out .= "</td></tr></table>";
		return $out;
	}
}

/*****************************************************************************************************
 Classe que gera um menu vertical
*/
class Menu {
	var $title;
	var $item;
	var $url;
	var $frame;
	var $width;
	
	// Construtor
	// $aTitle : título do menu
	// $width : largura do menu
	function Menu($aTitle="",$width="100%") {
		$this->title = $aTitle;
		$this->width = $width;
	}
	
	// adiciona item ao menu
	// $item : nome do item de menu
	// $url : link que será chamado
	// $frame : frame de destino
	function addItem($item, $url="#", $frame="content") {
		$this->item[] = $item;
		$this->url[] = $url;
		$this->frame[] = $frame;
	}
	
	// retorna bloco HTML que monta o menu
	function writeHTML() {
		$out = "";
		$out .= "<table border=0 cellspacing=0 cellpadding=0 width='".$this->width."'><tr><td vAlign='top'>";
		$out .= "<table class='FormTABLE'>";
		$out .= "<tr>";
		$out .= "<td class='FormHeaderTD'>";
		$out .= "<font class='FormHeaderFONT'>".$this->title."</font>";
		$out .= "</td>";
		$out .= "</tr>";
		for ($i = 0; $i < sizeof($this->item); $i++) {
			$out .= "<tr>";
			$out .= "<td class='DataTD'>";
			$out .= "<font class='DataFONT'>";
			$out .= "<a href='".$this->url[$i]."' target='".$this->frame[$i]."' class='link'>";
			$out .= $this->item[$i];
			$out .= "</a>";
			$out .= "</font>";
			$out .= "</td>";
			$out .= "</tr>";
		}
		$out .= "</table>";
		$out .= "</td></tr></table>";
		return $out;
	}
}

/*****************************************************************************************************
 Classe para gerar campo lookup
*/
class Lookup {
	var $title;
	var $nomeCampoForm;
	var $valorCampoForm;
	var $nomeTabela;
	var $nomeCampoChave;
	var $nomeCampoExibicao;
	var $nomeCampoAuxiliar;
	var $valorCampoFormDummy;
	var $sql;
	
	// define o nome do campo do formulário
	function setNomeCampoForm($umNome) {
		$this->nomeCampoForm = $umNome;
	}
	
	// define o nome do campo auxiliar que será exibido no lookup
	function setNomeCampoAuxiliar($umNome) {
		$this->nomeCampoAuxiliar = $umNome;
	}
	
	// define o título que aparecerá na janela de lookup
	function setTitle($umTitulo) {
		$this->title = $umTitulo;
	}
	
	// define o valor inicial do campo do formulário
	function setValorCampoForm($umValor) {
		$this->valorCampoForm = $umValor;
		$sql = "SELECT ".$this->nomeCampoExibicao.", ".$this->nomeCampoChave." FROM ".$this->nomeTabela
		     . " WHERE ".$this->nomeCampoChave."=".$this->valorCampoForm;
		$this->sql = $sql;
		$this->valorCampoFormDummy = getDbValue($sql);
	}
	
	// define o nome da tabela que será exibida no lookup
	function setNomeTabela($umNome) {
		$this->nomeTabela = $umNome;
	}
	
	// define o nome do campo chave que será devolvido ao campo do formulário
	function setNomeCampoChave($umNome) {
		$this->nomeCampoChave = $umNome;
	}
	
	// define o nome do campo que será exibido no lookup
	function setNomeCampoExibicao($umNome) {
		$this->nomeCampoExibicao = $umNome;
	}
	
	// retorna o bloco HTML que monta o campo lookup
	function writeHTML() {
		$out = "";
		$out .= "<input type='hidden' name='".$this->nomeCampoForm."' value='".$this->valorCampoForm."'>";
		$out .= "<input type='text' name='".$this->nomeCampoForm."Dummy' value='".$this->valorCampoFormDummy."' size='".LOOKUP_FIELDSIZE."' readonly>";
		$out .= "<img title=\"Clique aqui para abrir a lista de registros\" align='middle' style='cursor: pointer' src='". LOOKUP_IMAGEM ."' onClick=\"lookup(";
		$out .= "'".$this->nomeCampoForm."', '".$this->nomeTabela."', '".$this->nomeCampoChave."', '".$this->nomeCampoExibicao."', '".$this->nomeCampoAuxiliar."', '".$this->title."',450";
		$out .= ")\">";
		return $out;
	}
}


/*****************************************************************************************************
	Classe para criação de abas
*/
class Abas {
	var $item;
	var $status;
	var $url;
	var $level;
	
	// adiciona uma aba
	// $nome : nome da aba
	// $status : ativa? true ou false
	// $url : link que será chamado (usar somente se inativa)
	// $level : nível de acesso mínimo que o usuário deve ter para visualizar esta aba
	function addItem($nome="Geral", $status=false, $url="", $level=0) {
		$this->item[] = $nome;
		$this->status[] = $status;
		$this->url[] = $url;
		$this->level[] = $level;
	}
	
	// retorna bloco HTML que monta as abas
	function writeHTML() {
		$y = 2;
		$out  = "";
		$out .= "<table cellpadding='2' cellspacing='0' width='100%' border='0'>";
		$out .= "<tr>";
		$out .= "<td class='FundoABA' width='10px'>&nbsp;</td>";
		for ($x = 0; $x < sizeof($this->item); $x++) {
			if (isValidUser($this->level[$x])) {
				if ($this->status[$x]) {
					$out .= "<td nowrap class='SelecionadaABA'><font class='SelecionadaFontABA'>&nbsp;" . $this->item[$x] . "&nbsp;</font></td>";
				} else {
					$out .= "<td nowrap class='NaoSelecionadaABA'>";
					$out .= "<font class='NaoSelecionadaFontABA'>&nbsp;";
					$out .= "<a href='".$this->url[$x]."' target='content' class='aba'>";
					$out .= $this->item[$x];
					$out .= "</a>";
					$out .= "&nbsp;</font></td>";
				}
			}
			$out .= "<td class='FundoABA' width='1px'></td>";
			$y+=2;
		}
		$out .= "<td class='FundoABA' width='100%'>&nbsp;</td>";
		$out .= "</tr>";
		$out .= "<tr>";
		$out .= "<td colspan='$y' height='4px' class='SelecionadaABA'></td>";
		$out .= "</tr>";
		$out .= "</table>";
		return $out;
	}
}

/*****************************************************************************************************
	Classe para gerar deck de botões
*/
class Button {
	var $nome;
	var $url;
	var $target;
	var $level;
	
	/*
		Adiciona botão
		$nome : nome do botão
		$url : link que será chamado
		$target : frame em que o link será aberto
		$level : nível de acesso mínimo que o usuário deve ter para visualizar este botão
	*/
	function addItem($nome, $url, $target="content", $level=0) {
		$this->nome[] = $nome;
		$this->url[] = $url;
		$this->target[] = $target;
		$this->level[] = $level;
	}
	
	/*
		Retorna o código HTML com o deck de botões
	*/
	function writeHTML() {
		$out = "<div class='acoes'>";
		for ($x=0; $x<sizeof($this->nome); $x++) {
			if (isValidUser($this->level[$x])) {
				$out .= "&nbsp;".
				      "<a class='botao' href=\"".
						$this->url[$x].
						"\" target='".
						$this->target[$x].
						"'>&nbsp;".
						$this->nome[$x].
						"&nbsp;</a>";
			}
		}
		return $out."</div>";
	}
}

/*****************************************************************************************************
	Classe para controlar erros da página
*/
class Erro {
	var $strErro;
	function addErro($erro='') {
		$this->strErro .= $erro . '\n';
	}
	function hasErro() {
		return strlen($this->strErro)>0;
	}
	function toString() {
		return $this->strErro;
	}
}

/*****************************************************************************************************
	função para recuperar as variáveis GET e POST
*/
function getParam($param_name) {
	$param_value = "";
	if (isset($_POST[$param_name])) {
		$param_value = $_POST[$param_name];
	} else if(isset($_GET[$param_name])) {
		$param_value = $_GET[$param_name];
	}
	return $param_value;
}

/*****************************************************************************************************
	função para recuperar variáveis de sessão
*/
function getSession($param_name) {
	return $_SESSION[$param_name];
}

/*****************************************************************************************************
	função para definir variáveis de sessão
*/
function setSession($param_name, $param_value) {
	$_SESSION[$param_name] = $param_value;
}

/*****************************************************************************************************
	formatação de texto para exibição, pode ser adaptado conforme necessidade do sistema
*/
function formataTexto($texto) {
	// quebra de linha
	$texto = str_replace(chr(13),"<br>",$texto);

	return $texto;
}

/*****************************************************************************************************
	função para verificar a existência de chaves estrangeiras
	O MySQL não implementa integridade refencial
	table -> tabela alvo
	key -> chave da tabela alvo
	val -> valor da chave estrangeira
*/
function checkFK($table, $key, $val) {
	$sql = "SELECT count($key) FROM $table WHERE $key=$val";
	$qt = getDbValue($sql);
	return ($qt>0);
}


/*****************************************************************************************************
	monta select de data
*/
function formDate($nome_campo, $data="") {
	//----- monta select de dia
	if ($data!="") {
		$aData = explode("-",$data);
		$dia_hoje = $aData[2];
		$mes_hoje = $aData[1];
		$ano_hoje = $aData[0];
	}
	echo "<select name=\"" . $nome_campo . "_dia\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=1; $i <= 31; $i++) {
		$xdia = $i < 10?"0".$i:$i;
		$selected = $dia_hoje==$xdia?" selected":"";
		echo "<option value=\"" . $xdia . "\" $selected>" . $xdia . "</option>\n";
	}
	echo "</select>\n";
	
	//----- monta select do mes
	$aMes = array("nulo","Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro");
	echo "&nbsp;<select name=\"" . $nome_campo . "_mes\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=1; $i <= 12; $i++) {
		$xmes = $i < 10?"0".$i:$i;
		$selected = $mes_hoje==$xmes?" selected":"";
		echo "<option value=\"" . $xmes . "\" $selected>" . $aMes[$i] . "</option>\n";
	}
	echo "</select>\n";
	
	//----- monta select de ano
	echo "&nbsp;<select name=\"" . $nome_campo . "_ano\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=date("Y"); $i <= date("Y")+1; $i++) {
		$selected = $ano_hoje==$i?" selected":"";
		echo "<option value=\"" . $i . "\" $selected>" . $i . "</option>\n";
	}
	echo "</select>\n";
}

/*****************************************************************************************************
	monta select de hora
*/
function formTime($nome_campo, $hora="") {
	//----- monta select de hora
	if ($hora!="") {
		$aHora = explode(":",$hora);
		$hora_hoje = $aHora[0];
		$min_hoje = $aHora[1];
	}
	
	echo "<select name=\"" . $nome_campo . "_hora\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=0; $i <= 23; $i++) {
		$xhora = $i < 10?"0".$i:$i;
		$selected = $hora_hoje==$xhora?" selected":"";
		echo "<option value=\"" . $xhora . "\" $selected>" . $xhora . "</option>\n";
	}
	echo "</select>\n";

	//----- monta select do minuto
	echo "&nbsp;<select name=\"" . $nome_campo . "_minuto\">\n";
	echo "<option value=\"\">--</option>\n";
	for ($i=0; $i <= 55; $i+=5) {
		$xmin = $i < 10?"0".$i:$i;
		$selected = $min_hoje==$xmin?" selected":"";
		echo "<option value=\"" . $xmin . "\" $selected>" . $xmin . "</option>\n";
	}
	echo "</select>\n";
}

/*****************************************************************************************************
	gerador de listbox
	$sql : expressão sql que monta a lista (selecionar apenas 2 campos com os nomes "id" e "val"
	$name : nome do campo que será criado
	$default : valor inicial do campo
	$todos : texto indicativo, caso a lista permita valor null
	$js : expressão javascript
*/
function listboxField($sql, $name, $default=0, $todos="", $js="") {
	$connTemp = new db();
	$connTemp->open();
	$rs = new query($connTemp,$sql);
	$result="<select name=\"$name\" id=\"$name\" size=1 $js>\n";
	if ($todos!="") {
		$result.= "<option value=\"\">$todos</option>\n";
	}
	while ($rs->getrow()) {
		$id = $rs->field($rs->fieldname(0));
		$val = substr($rs->field($rs->fieldname(1)),0,60);
		if ($default == $id) {$selected="selected";} else {$selected="";}
		$result.="<option value=\"$id\" $selected>$val</option>\n";
	}
	$result.="</select>\n";
	$connTemp->close();
	return $result;
} 

/*****************************************************************************************************
	verifica se usuário pode acessar página
	$nivel : valor numérico que define o nível hierárquico de acesso
*/
function verificaUsuario($nivel=0) {
	if ($nivel > 0) {
		if (getSession("sis_apl")!=SIS_APL_NAME) {
			redirect("../common/login.php?querystring=".urlencode(getenv("QUERY_STRING"))."&ret_page=".urlencode(getenv("REQUEST_URI")));
			die();
		} else if ((!isset($_SESSION["sis_level"]) || getSession("sis_level") < $nivel)) {
			redirect("../common/login.php?querystring=".urlencode(getenv("QUERY_STRING"))."&ret_page=".urlencode(getenv("REQUEST_URI")));
			die();
		}
	}
}

/*****************************************************************************************************
	função que verifica se o usuario está dentro do nível
	retorna boolean
*/
function isValidUser($level=0) {
	return (($level==0)||(getSession("sis_level")>=$level));
}

/*****************************************************************************************************
	gera senha aleatória
*/
function geraSenha($tamanho=6) {
	$senha = "abcdefghjkmnpqrstuvxzwyABCDEFGHIJLKMNPQRSTUVXZYW23456789";
	srand ((double)microtime()*1000000);
	for ($i=0; $i<$tamanho; $i++) {
		$password .= $senha[rand()%strlen($senha)];
	}
	return $password;
}

/*****************************************************************************************************
	retorna o valor de um campo através de expressão sql
*/
function getDbValue($sql) {
	$connTemp = new db();
	$connTemp->open();
	$rs = new query($connTemp, $sql);
	if($rs->numrows()<1) {
		$valor = "";
	} else {
		$rs->getrow();
		$nomecampo = $rs->fieldname(0);
		$valor = $rs->field($nomecampo);
	}
	$rs->free();
	$connTemp->close();
	return $valor;
}

/*****************************************************************************************************
	Soma numero de dias a uma data
	Sintaxe: somadata( "01/12/2002",5 );
	Retorno: 06/12/2002
*/ 
function somadata($data, $nDias) {
	if (!isset( $nDias )) {
		$nDias = 1;
	}
	$aVet = Explode("/",$data);
	return date("d/m/Y",mktime(0,0,0,$aVet[1],$aVet[0]+$nDias,$aVet[2]));
}

/*****************************************************************************************************
	Função para gerar campos radio
	$arr : array de valores, cada elemento deve ter a chave e o label separados por vírgula
	       exemplo: {"1,Solteiro","2,Casado","3,Separado"}
	$name : nome do campo
	$sel : valor inicial do campo
	$js : expressão javascript
*/
function radioField($arr,$name,$sel = "", $js="") {
	$out = "";
	
	while (list($key, $val) = each($arr)) {
		$string = explode(",",$val);
		$label = $string[1];
		$valor = $string[0];
		$select_v = ($sel && $valor == $sel)?" checked":"";
		$out .= "<input type=radio name=\"$name\" value=\"$valor\" $select_v $js> $label<br>\n";
	}
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo de data com calendário popup
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
*/
function dateField($fieldname, $fieldvalue="", $js="") {
	$out = "";
	$out .= "<input type='text' id='$fieldname' name='$fieldname' value='$fieldvalue' size='11' maxlength='10' $js>";
	$out .= "<a href=\"javascript:showCalendar('$fieldname')\">";
	$out .= "<img src='../inc/calendario/calendario.gif' border='0'>";
	$out .= "</a>";
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo de texto
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$lenght : tamanho do campo
	$maxlenght : capacidade do campo
	$js : expressão javascript
*/
function textField($fieldname, $fieldvalue="", $length=40, $maxlength=40, $js="") {
	$out = "";
	$out .= "<input type='text' name='$fieldname' value='$fieldvalue' size='$length' maxlength='$maxlength' $js>";
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo de password
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$lenght : tamanho do campo
	$maxlenght : capacidade do campo
	$js : expressão javascript
*/
function passwordField($fieldname, $fieldvalue="", $lenght=20, $maxlenght=20, $js="") {
	$out = "";
	$out .= "<input type='password' name='$fieldname' value='$fieldvalue' size='$lenght' maxlenght='$maxlenght' $js>";
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo de checkbox
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$expr : expressão booleana que define se o checkbox está marcado ou não
	$js : expressão javascript
*/
function checkboxField($fieldname, $fieldvalue="", $expr, $js="") {
	$out = "";
	$checked = $expr?" checked":"";
	$out .= "<input type='checkbox' name='$fieldname' value='$fieldvalue' $checked $js>";
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo file
	$fieldname : nome do campo que será criado
	$fieldvalue : valor inicial do campo
	$expr : expressão que retorna um boolean
	$js : expressão javascript
*/
function fileField($fieldname, $fieldvalue="", $lenght=30, $js="") {
	$out = "";
	$out .= "<input type='hidden' name='".$fieldname."_anterior' value='$fieldvalue'>";
	$out .= "<input type='file' name='$fieldname' size='$lenght' $js>";
	if (strlen(trim($fieldvalue))>0) {
		$out .= "<br>".FILEFIELD_ARQUIVOATUAL." <b>" . $fieldvalue . "</b>&nbsp;" . "<input type='checkbox' name='".$fieldname."_excluir' value='1'> ".FILEFIELD_REMOVER;
	}
	return $out;
}

/*****************************************************************************************************
	Função para gerar lista de campos checkbox
	$formField : nome do campo no formulário
	$formFieldValue : valor do campo no formulário
	$table : nome da tabela que formará os checkboxes
	$keyField : campo chave da tabela
	$showField : campo que será exibido nos checkboxes
	$condition : condição de exibição dos registros (cláusula WHERE)
*/
function multipleCheckboxField ($formField, $formFieldValue, $table, $keyField, $showField, $condition="") {
	$connTemp = new db();
	$connTemp->open();
	if ($condition!="") $where = "WHERE $condition";
	$sql = "SELECT $keyField, $showField FROM $table $where ORDER BY $showField";
	$rs = new query($connTemp, $sql);
	$lista = explode(",",$formFieldValue);
	$out = "";
	while ($rs->getrow()) {
		$checked = "";
		if (in_array($rs->field($keyField),$lista)) $checked = " checked";
		$out .= "<input type='checkbox' name='".$formField."[]' id='$formField' value='".
		        $rs->field($keyField).
				  "' $checked> ".
				  $rs->field($showField).
				  "<br>";
	}
	$connTemp->close();
	return $out;
}

/*****************************************************************************************************
        Função para gerar lista de campos checkbox
        $formField      : nome do campo no formulário
        $formFieldValue : valor do campo no formulário (valores separados por ,)
        $table          : nome da array que formará os checkboxes ("0,Teste")
*/
function multipleCheckboxArray ($formField, $formFieldValue, $elementos) {
	$lista     = explode(",",$formFieldValue);
	$out       = "";
	$qtd       = count($elementos);
	for ($i=0;$i<$qtd;$i++){
		$checked = "";
		$dado    = Explode(",",$elementos[$i]);
		if (in_array($dado[0],$lista)) $checked = " checked";
		$out .= "<input type='checkbox' name='".$formField."[]' id='$formField' value='".
		        $dado[0].
				"' $checked> ".
				$dado[1].
				"<br>";
	}
	return $out;
}

/*****************************************************************************************************
	Função para gerar campo textarea com controle de caracteres via javascript
	$nome_campo : nome do campo que será criado
	$valor_inicial : valor inicial do campo
	$num_linhas : número de linhas do campo
	$num_colunas : número de colunas do campo
	$maximo : quantidade máxima de caracteres
*/
function textAreaField($nome_campo, $valor_inicial="", $num_linhas=5, $num_colunas=40, $maximo=200) {
	$str = "<textarea ".
	       "name='$nome_campo' ".
		   "rows='$num_linhas' ".
		   "cols='$num_colunas' ".
		   "onKeyPress='textCounter(this,this.form.".$nome_campo."_counter,$maximo);' ".
		   "onKeyUp='textCounter(this,this.form.".$nome_campo."_counter,$maximo);' ".
		   ">".
		   $valor_inicial.
		   "</textarea><br>".
		   "<input class='DataTD' ".
		   "style='border: 0px; text-align: right' ".
		   "type='text' ".
		   "name='".$nome_campo."_counter' ".
		   "maxlength='4' readonly size='4' value='".($maximo-strlen($valor_inicial))."'> ".TEXTAREA_RESTANTES;
	return $str;
}

/*****************************************************************************************************
	Função para gerar link html
*/
function addLink($titulo, $url, $alt="", $target="content") {
	return "<a title='$alt' class='link' href='$url' target='$target'>$titulo</a>";
}

/*****************************************************************************************************
 Função para verificar campo duplicado
*/
function isDuplicated($tabela, $campo_valor, $campo_chave, $valor, $chave) {
	$retorno = false;
	if (strlen($valor)) {
		$iCount = 0;
		if ($chave=="") {
			$iCount = getDbValue("SELECT count(*) AS qtde FROM $tabela WHERE $campo_valor='$valor'");
		} else {
			$iCount = getDbValue("SELECT count(*) AS qtde FROM $tabela WHERE $campo_valor='$valor' AND NOT ($campo_chave=$chave)");
		}
		if ($iCount > 0) $retorno = true;
	}
	return $retorno;
}                   

/*****************************************************************************************************
 Tratamento da data para formatos apenas numéricos
 Recebe uma data no formato yyyymmdd, coloca as barras e ordena em dd/mm/yyyy
*/
function dtod($data) {
     $data_ano = substr($data,0,4);
     $data_mes = substr($data,4,2);
     $data_dia = substr($data,6,2);
     return $data_dia."/".$data_mes."/".$data_ano;  
}

/*****************************************************************************************************
	Converte yyyy-mm-dd hh:mm:ss em dd/mm/yyyy hh:mm:ss
	função auxiliar, use stod()
*/
function _stodt($str) {
	$aStr = explode($str, " ");
	$d = $aStr[0];
	$t = $aStr[1];
	$aD = explode($d,"-");
	$datetime = $aD[2] . "/" . $aD[1] . "/" . $aD[0] . " " . $t;
	return $datetime;
}

/*****************************************************************************************************
	Converte dd/mm/yyyy hh:mm:ss em yyyy-mm-dd hh:mm:ss
	função auxiliar, use dtos()
*/
function _dttos($datetime) {
	$aDT = explode($str, " ");
	$s = $aDT[0];
	$t = $aDT[1];
	$aS = explode($s, "/");
	$str = $aS[2] . "-" . $aS[1] . "-" . $aS[0] . " " . $t;
	return $str;
}

/*****************************************************************************************************
	converte AAAA-MM-DD em DD/MM/AAAA
*/
function stod($texto) {
	if ($texto=="") return "";
	if (strlen($texto)>10) {
		return _stodt($texto);
	} else {
		$data = explode("-",$texto);
		return $data[2] . "/" . $data[1] . "/" . $data[0];
	}
}

/*****************************************************************************************************
	converte DD/MM/AAAA para AAAA-MM-DD
*/
function dtos($data) {
	if ($data=="") return "";
	if (strlen($data)>10) {
		return _dttos($data);
	} else {
		$texto = explode("/",$data);
		return $texto[2] . "-" . $texto[1] . "-" . $texto[0];
	}
}

/*****************************************************************************************************
 Função para formatar data
*/
function fdata($data,$formato="d/m/Y"){
	$months = array("january"=>"Janeiro","february"=>"Fevereiro","march"=>"Março","april"=>"Abril","may"=>"Maio","june"=>"Junho","july"=>"Julho","august"=>"Agosto","september"=>"Setembro","october"=>"Outubro","november"=>"Novembro","december"=>"Dezembro");
	$weeks = array("sunday"=>"Domingo","monday"=>"Segunda","tuesday"=>"Terça","wednesday"=>"Quarta","thursday"=>"Quinta","friday"=>"Sexta","saturday"=>"Sábado");
	$months3 = array("jan"=>"jan","feb"=>"fev","mar"=>"mar","apr"=>"abr","may"=>"mai","jun"=>"jun","jul"=>"jul","aug"=>"ago","sep"=>"set","oct"=>"out","nov"=>"nov","dec"=>"dez");
	$weeks3 = array("sun"=>"dom","mon"=>"seg","tue"=>"ter","wed"=>"qua","thu"=>"qui","fri"=>"sex","sat"=>"sab");
	
	$data = strtolower(date($formato,strtotime($data)));
	$data = strtr($data,$months);
	$data = strtr($data,$weeks);
	$data = strtr($data,$months3);
	$data = strtr($data, $weeks3);
	return $data;
}

/*****************************************************************************************************
	Ajuda on-line
	Gera um ícone na página que quando clicado abre uma janela popup
	$titulo : título da ajuda
	$msg : texto da ajuda
*/
function help($titulo="",$msg="") {
	$out = "";
	$out .= "&nbsp;<img title=\"Clique aqui para obter ajuda\" style=\"cursor: pointer\" align=middle src=\"" . HELP_IMAGEM . "\" ".
           "onclick=\"hint=window.open('', 'hint', 'width=400, height=300, resizable=no, scrollbars=yes, top=80, left=450');".
           "hint.document.write('<HTML><HEAD><TITLE>AJUDA</TITLE></HEAD><BODY onClick=\'self.close();\' style=\'background-color: ".HELP_CORFUNDO."\'>');".
           "hint.document.write('<P style=\'font-size: ".HELP_TAMANHOTITULO."; font-weight: bold; color: ".HELP_CORTITULO."; font-family: ".HELP_FONTTITULO."\'>');".
           "hint.document.write( '$titulo' );".
           "hint.document.write('</P>');".
           "hint.document.write('<P style=\'font-size: ".HELP_TAMANHOTEXTO."; color: ".HELP_CORTEXTO."; font-family: ".HELP_FONTTEXTO."\'>');".
           "hint.document.write( '$msg' );".
           "hint.document.write('</P>');".
           "hint.document.write('</BODY></HTML>');".
           "\">&nbsp";
	return $out;
}

/*****************************************************************************************************
	Desenho de título da página
*/
function pageTitle($titulo,$subtitulo="") {
	if ($titulo != "") {
		echo "<div class='titulo'>$titulo</div>";
	}
	if ($subtitulo != "") {
		echo "<div class='subtitulo'>$subtitulo</div>";
	}
	echo "<hr noshade class='linha'>";
}

/*****************************************************************************************************
	Exibição de alert em javascript
*/
function alert($msg) {
	echo "<script language='JavaScript'>";
	echo "alert('$msg');";
	echo "</script>";
}

/*****************************************************************************************************
	Provoca redirect via javascript
*/
function redirect($url, $target="content") {
	echo "<script language='JavaScript'>";
	echo "parent.$target.document.location='$url';";
	echo "</script>";
}

/*****************************************************************************************************
	Provoca redirect via javascript
*/
function redirectportal($url, $target="content") {
	echo "<script language='JavaScript'>";
	echo "document.location='$url';";
	echo "</script>";
}

/*****************************************************************************************************
	Cria scroll no conteúdo enviado
*/
function scrollBlock($conteudo="", $altura="300px", $largura="100%") {
   $out  = "<div style='background-color: #FFFFFF; height: $altura; width: $largura; ";
   $out .= "overflow: auto; border: 0px; padding: 1px;'>";
   $out .= $conteudo;
   $out .= "</div>";
   return $out;
}

/*****************************************************************************************************
 Limita o tamanho de um texto colocando "..." no final da string
*/
function strLimit($str, $size, $showDots = false) {
	if (strlen($str) > $size) {
		$tmp = substr($str, 0, $size);
		$p = strrpos($tmp, ' ');
		if ($p) {
			$str = substr($tmp, 0, $p);
		} else {
			$str = $tmp;
		}
		return $str . ($showDots ? "..." : "");
	} else {
		return $str;
	}
}
/*****************************************************************************************************
	retorna um array de valores através de expressão sql que retorna 2 campos, codigo e outro
*/
function getDbArray($sql) {
	$connTemp = new db();
	$connTemp->open();
	$rs = new query($connTemp, $sql);
	while($rs->getrow()) {
		$codField = $rs->fieldname(0);
		$nomeField = $rs->fieldname(1);
		$valor[$rs->field($codField)] = $rs->field($nomeField);
	}
	$rs->free();
	$connTemp->close();
	return $valor;
}

/*****************************************************************************************************
 função que gera a senha para o usuário e envia a senha para o email definido no cadastro e na troca de senha
*/
 
function geraSenhaMailReseta($nome,$usuario,$email,$incAtu=1,$sistema = SIS_APL_NAME,$sistemaEmail = SIS_APL_EMAIL,$sistemaEndereco = SIS_APL_URL) {
   $senha = geraSenha(6);
 
 if ($incAtu == 1) {
		   $message = "Você foi cadastrado como um novo usuário no site ".$sistema."<br><br>";
	  } 
		else {
		   $message = "Sua senha foi reiniciada no site ".$sistema."<br><br>";
	  }
		$message .= "Seguem abaixo os dados de acesso:<br><br>";
		$message .= "Endereço: <a href='".$sistemaEndereco."' target='_blank'>".$sistemaEndereco."</a><br><br>";
		$message .= "Usuário: " . $usuario . "<br>";
		$message .= "Senha: " . $senha . "<br><br>";
		$message .= "Observação: Esta senha é gerada automaticamente, você poderá trocá-la a qualquer momento na área de usuário do site.<br><br>";
		$message .= "Atenciosamente,<br>";
		$message .= "Administrador ".$sistema;
 
$Mailer = new PHPMailer();
 
// define que será usado SMTP
$Mailer->IsSMTP();
 
// envia email HTML
$Mailer->isHTML(true);
 
// codificação UTF-8, a codificação mais usada recentemente
$Mailer->Charset = 'UTF-8';
 
// Configurações do SMTP
$Mailer->SMTPAuth = true;
$Mailer->SMTPSecure = 'ssl';
$Mailer->Host = 'smtp.reequilibrio.com.br';
$Mailer->Port = 465;
$Mailer->Username = 'clinica@reequilibrio.com.br';
$Mailer->Password = 'reeq2016';
 
// E-Mail do remetente (deve ser o mesmo de quem fez a autenticação
// nesse caso seu_login@gmail.com)
$Mailer->From = 'clinica@reequilibrio.com.br';
 
// Nome do remetente
$Mailer->FromName = 'Reequilibrio.com.br';
 
// assunto da mensagem
$Mailer->Subject = 'Reequilibrio';
 
// corpo da mensagem
$Mailer->Body = $message;
 
// corpo da mensagem em modo texto
//$Mailer->AltBody = 'Mensagem em texto';
 
// adiciona destinatário (pode ser chamado inúmeras vezes)
$Mailer->AddAddress($email);
 
// adiciona um anexo
//$Mailer->AddAttachment('arquivo.pdf');
 
// verifica se enviou corretamente
if ($Mailer->Send())
{

		   return $senha;
	
}
else
{
	echo 'Erro do PHPMailer: ' . $Mailer->ErrorInfo;
}
}

/*****************************************************************************************************
 função que gera a senha para o usuário e envia a senha para o email definido no cadastro e na troca de senha
*/

function geraSenhaMail($nome,$usuario,$email,$incAtu=1,$sistema = SIS_APL_NAME,$sistemaEmail = SIS_APL_EMAIL,$sistemaEndereco = SIS_APL_URL) {
   $senha = geraSenha(6);

 if ($incAtu == 1) {
		   $message = "Você foi cadastrado como um novo usuário no site ".$sistema."<br><br>";
	  } 
		else {
		   $message = "Sua senha foi reiniciada no site ".$sistema."<br><br>";
	  }
		$message .= "Seguem abaixo os dados de acesso:<br><br>";
		$message .= "Endereço: <a href='".$sistemaEndereco."' target='_blank'>".$sistemaEndereco."</a><br><br>";
		$message .= "Usuário: " . $usuario . "<br>";
		$message .= "Senha: " . $senha . "<br><br>";
		$message .= "Observação: Esta senha é gerada automaticamente, você poderá trocá-la a qualquer momento na área de usuário do site.<br><br>";
		$message .= "Atenciosamente,<br>";
		$message .= "Administrador ".$sistema;
 
$Mailer = new PHPMailer();
 
// define que será usado SMTP
$Mailer->IsSMTP();
 
// envia email HTML
$Mailer->isHTML(true);
 
// codificação UTF-8, a codificação mais usada recentemente
$Mailer->Charset = 'UTF-8';
 
// Configurações do SMTP
$Mailer->SMTPAuth = true;
$Mailer->SMTPSecure = 'ssl';
$Mailer->Host = 'smtp.reequilibrio.com.br';
$Mailer->Port = 465;
$Mailer->Username = 'clinica@reequilibrio.com.br';
$Mailer->Password = 'reeq2016';
 
// E-Mail do remetente (deve ser o mesmo de quem fez a autenticação
// nesse caso seu_login@gmail.com)
$Mailer->From = 'clinica@reequilibrio.com.br';
 
// Nome do remetente
$Mailer->FromName = 'Reequilibrio.com.br';
 
// assunto da mensagem
$Mailer->Subject = 'Reequilibrio';
 
// corpo da mensagem
$Mailer->Body = $message;
 
// corpo da mensagem em modo texto
//$Mailer->AltBody = 'Mensagem em texto';
 
// adiciona destinatário (pode ser chamado inúmeras vezes)
$Mailer->AddAddress($email);
 
// adiciona um anexo
//$Mailer->AddAttachment('arquivo.pdf');
 
// verifica se enviou corretamente
if ($Mailer->Send())
{

		   return $senha;
	
}
else
{
	echo 'Erro do PHPMailer: ' . $Mailer->ErrorInfo;
}
}

/**************************************************************************
 retorna a hora atual do banco
*/
function dbnow(){
	return getDbValue("SELECT NOW()");
}

/**************************************************************************
	grava a data da ultima modificação na tabela definida - criado por necessidade do gesite
	ex de chamada: 
	atualizaModificacao($conn,"orgao","cod_orgao",getParam("f_id"),dbnow());
*/
function atualizaModificacao($conn,$tabela,$campoID,$id,$datahora) {
   $sql = "UPDATE ".$tabela." SET dth_atualizacao='".$datahora."' WHERE ".$campoID."='".$id."'";
   $rs = new query($conn, $sql); 
}

/*****************************************************************************************************
	Classe upload de arquivos
	Tipo: TEXT, IMAGE, ALL
*/

class Upload {
   var $arquivo;
   var $nomeArquivo;
		var $filtro="ALL";
		var $tamanho=3000000;
		var $diretorio;
		var $erro="";
		var $largura;
		var $altura;
   
		function setArquivo($arquivo){
		   $this->arquivo   = $arquivo;
			 if ($arquivo["name"] == "" ) { 
			    return 0; 
			 }
			 else {
			    $this->nomeArquivo = date("U").removeAcentos($arquivo["name"]);
					return 1;
			 }
	  }
		function getNomeArquivo() {
		   return $this->nomeArquivo;
		}
		function setFiltro($filtro){
		   $this->filtro   = $filtro;
		}
		function setTamanho($tamanho){
		   $this->tamanho   = $tamanho;
		}
		function setDiretorio($diretorio){
		   $this->diretorio = $diretorio;
   }
		function setLargura($largura){
		   $this->largura   = $largura;
   }
		function setAltura($altura){
		   $this->altura = $altura;
   }
				
   function valida(){
	     if ($this->filtro=="IMAGE"){
         if (!eregi("^image\/(pjpeg|jpeg|jpg|png|gif|bmp)$", $this->arquivo["type"])) {
            $this->erro .= 'Arquivo em formato inválido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo.\n\n';
         }
	        $tamanhos = getimagesize($this->arquivo['tmp_name']);
         if (($this->largura != "")&&($tamanhos[0] > $this->largura)) {
            $this->erro .= 'Largura da imagem não deve ultrapassar ' . $this->largura . ' pixels\n';
         }
         if (($this->altura != "")&&($tamanhos[1] > $this->altura)) {
            $this->erro .= 'Altura da imagem não deve ultrapassar ' . $this->altura . ' pixels\n';
         }
      }
	     if ($this->filtro=="DOC"){
         if (!eregi("^application\/(msword|pdf)$", $this->arquivo["type"])) {
            $this->erro .= 'Arquivo em formato inválido! O arquivo deve ser doc ou pdf. Envie outro arquivo.\n\n';
         }
	     }
	     if ($this->filtro=="VIDEO"){
         if (!eregi("^video\/(mpeg|quicktime|midi|x-ms-wmv|avi|msvideo|x-msvideo)$", $this->arquivo["type"])) {
            $this->erro .= 'Arquivo em formato inválido! O arquivo deve ser em formato de vídeo.\n\n';
         }
	     }
	     if ($this->filtro=="AUDIO"){
         if (!eregi("^audio\/(mpeg|x-wav|wav|x-realaudio|x-pn-realaudio)$", $this->arquivo["type"])) {
            $this->erro .= 'Arquivo em formato inválido! O arquivo deve ser em formato de vídeo.\n\n';
         }
	     }
        
			 if ($this->arquivo['size']>$this->tamanho) {
         $this->erro .= "Tamanho máximo permitido é $this->tamanho.\n\n";
      }
		   return $this->erro;
   }
		
		function envia(){
      if (sizeof($this->erro) > 0) {
         move_uploaded_file($this->arquivo['tmp_name'],$this->diretorio.$this->nomeArquivo);
					return true;
      }
			 else {
			 
		      return false;
			 }
   }
		
		function excluir($arquivo) {
		   $arq = $this->diretorio.$arquivo;
			 $resultado = unlink($arq);
			 return $resultado;
		}
}

/***************************************************************************
	Função para retirar acentuação e espaço em branco 
*/
function removeAcentos($str) {
   $str = ereg_replace("[^a-zA-Z0-9_.]","", strtr($str, "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ -º°", "aaaaeeiooouucAAAAEEIOOOUUC____"));
   return $str; 
}

/***************************************************************************
	Função para carregar os includes javascript necessários
	opções, popcalendar, lookup, focus, textcounter, editor 
*/
function carregaJS($argv="popcalendar,lookup,focus,textcounter,editor") {
   $str  = "";
		$argv = ".,".$argv;
		if(strpos($argv,"popcalendar")) 	$str .= "<script language=\"javascript\" src=\"../inc/calendario/popcalendar.js\"></script>\n";
		if(strpos($argv,"lookup")) 	    $str .= "<script language=\"javascript\" src=\"../inc/js/lookup.js\"></script>\n";
		if(strpos($argv,"focus")) 	      $str .= "<script language=\"JavaScript\" src=\"../inc/js/focus.js\"></script>\n";
		if(strpos($argv,"textcounter")) 	$str .= "<script language=\"JavaScript\" src=\"../inc/js/textcounter.js\"></script>\n";
		if(strpos($argv,"editor")) {
			$str .= "<script language=\"javascript\" type=\"text/javascript\" src=\"../inc/tinymce/jscripts/tiny_mce/tiny_mce.js\"></script>\n";
		  $str .= "<script language=\"javascript\" type=\"text/javascript\">\n";
     $str .= "   tinyMCE.init({\n";
     $str .= "   theme : \"advanced\",\n";
		  $str .= "   mode : \"textareas\",\n";
 	  $str .= "   editor_selector : \"mceEditor\",\n";
		  $str .= "   language : \"pt_br\",\n";
		  $str .= "   theme_advanced_disable: \"outdent,indent,separator,strikethrough,formatselect,styleselect,bold,italic,underline,justifyleft,justifycenter,justifyright,justifyfull,removeformat,sub,sup,charmap,hr,visualaid,\",\n"; 
		  $str .= "   plugins : \"advlink,iespell,paste,noneditable\",\n";
		  $str .= "   paste_create_paragraphs : \"false\",\n";
		  $str .= "   theme_advanced_buttons1_add_before: \"bold,italic,underline,cut,copy,justifyleft,justifycenter,justifyright,justifyfull,paste,pasteword,pastetext\",\n";
		  $str .= "   theme_advanced_toolbar_location : \"top\",\n";
		  $str .= "   theme_advanced_buttons2_add: \"hr,removeformat,sub,sup,charmap\",\n";
		  $str .= "   theme_advanced_toolbar_align : \"left\",\n";
		  $str .= "   theme_advanced_statusbar_location : \"bottom\",\n";
		  $str .= "   paste_use_dialog : \"false\",\n";
		  $str .= "   theme_advanced_resizing : \"true\",\n";
		  $str .= "   theme_advanced_resize_horizontal : \"true\",\n";
			$str .= "   theme_advanced_path : \"false\"\n";
			$str .= "});\n";
     $str .= "</script>\n";
		}	
		return $str;
} 

/*****************************************************************************************************
	verifica se usuário pode acessar página
	$nivel : string separada por vírgulas que contem os niveis permitidos
*/
function verificaPermissaoPagina($nivel="0") {
	  $perm = verificaPermissao($nivel);
		if (!$perm) {
	     redirect("../common/login.php?querystring=".urlencode(getenv("QUERY_STRING"))."&ret_page=".urlencode(getenv("REQUEST_URI")));
		   die();
	  } 
}

/*****************************************************************************************************
	verifica se o nível tem permissão, retorna true ou false
	$nivel : string separada por vírgulas que contem os niveis permitidos
*/
function verificaPermissao($nivel="0") {
	  $nivel = explode(",",$nivel);
	  $ret = false;
		if (getSession("sis_apl")!=SIS_APL_NAME) {
	     $ret = false;
		} 
	  else {
	     for($i=0;$i<sizeof($nivel);$i++) {
		      if(getSession("sis_level") == $nivel[$i]) $ret = true;
		   }
	  }
   return $ret;
}



/*****************************************************************************************************
	Função para gerar campo textarea com editor html
	$nome_campo : nome do campo que será criado
	$valor_inicial : valor inicial do campo
	$num_linhas : número de linhas do campo
	$num_colunas : número de colunas do campo
*/
function textAreaFieldEditor($nome_campo, $valor_inicial="", $num_linhas=5, $num_colunas=40) {
	$str = "<textarea ".
      "name='$nome_campo' ".
			 "class='mceEditor' ".
	     "rows='$num_linhas' ".
		   "cols='$num_colunas' ".
		   ">".
		   $valor_inicial.
		   "</textarea>";
	return $str;
}

/*****************************************************************************************************
 Solicita confirmação e redireciona em javascript
*/
function confirmRedirect($msg, $destino, $target = "content") {
 echo "<script language='JavaScript'>";
 echo "if(confirm('$msg'))";
    echo "parent.$target.document.location='$destino';";
 echo "</script>";
}
?>
