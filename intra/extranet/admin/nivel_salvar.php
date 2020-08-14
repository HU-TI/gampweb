<?
/*
	Transa��o de inclus�o/altera��o de registros
*/
include("../inc/common.php");

define("OBJETO","nivel");
define("OBJETO_TABELA","usuario_nivel");
define("OBJETO_TITULO","N�veis");
define("OBJETO_TITULO_SINGULAR","N�vel");
define("DIRETORIO","admin");

/*
	verifica��o do n�vel do usu�rio
*/
verificaUsuario(10);

/*
	conex�o com o banco de dados
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
	valida��o,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_".OBJETO)=="")          $erro->addErro('Nome de '.OBJETO.' deve ser informado.');

/*
	Atualiza��o dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na valida��o
	// objeto para montagem de express�o sql
	$sql = new UpdateSQL();
	
	$sql->setTable(OBJETO_TABELA);
	$sql->setKey("cod_".OBJETO,          getParam("f_id"),              "Number");
	
	$sql->addField(OBJETO,               getParam("f_".OBJETO),         "String");
	
	if (strlen(getParam("f_id"))>0) { // altera��o, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../".DIRETORIO."/".OBJETO."_lista.php"; 
	} else { // inclus�o
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../".DIRETORIO."/".OBJETO."_lista.php"; 
	}
	
	// volta para a lista ou reapresenta o formul�rio em modo de edi��o
	redirect($destino,"content");
} else { // n�o passou na valida��o
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conex�o com o banco de dados
*/
$conn->close();
?>