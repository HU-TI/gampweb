<?
/*
	Transaзгo de inclusгo/alteraзгo de registros
*/
include("../inc/common.php");

define("OBJETO","nivel");
define("OBJETO_TABELA","usuario_nivel");
define("OBJETO_TITULO","Nнveis");
define("OBJETO_TITULO_SINGULAR","Nнvel");
define("DIRETORIO","admin");

/*
	verificaзгo do nнvel do usuбrio
*/
verificaUsuario(10);

/*
	conexгo com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	tratamento de campos,
	configure conforme sua necessidade,
	siga o exemplo abaixo
*/
//$data_cadastro = dtos(getParam("f_data_cadastro"));
//$ativo         = strlen(getParam("f_ativo"))==0?"0":getParam("f_ativo");
//$descricao     = addslashes(getParam("f_descricao"));

/*
	validaзгo,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_".OBJETO)=="")          $erro->addErro('Nome de '.OBJETO.' deve ser informado.');

/*
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na validaзгo
	// objeto para montagem de expressгo sql
	$sql = new UpdateSQL();
	
	$sql->setTable(OBJETO_TABELA);
	$sql->setKey("cod_".OBJETO,          getParam("f_id"),              "Number");
	
	$sql->addField(OBJETO,               getParam("f_".OBJETO),         "String");
	
	if (strlen(getParam("f_id"))>0) { // alteraзгo, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../".DIRETORIO."/".OBJETO."_lista.php"; 
	} else { // inclusгo
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../".DIRETORIO."/".OBJETO."_lista.php"; 
	}
	
	// volta para a lista ou reapresenta o formulбrio em modo de ediзгo
	redirect($destino,"content");
} else { // nгo passou na validaзгo
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conexгo com o banco de dados
*/
$conn->close();
?>