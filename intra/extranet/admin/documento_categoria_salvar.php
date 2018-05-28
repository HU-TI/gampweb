<?
/*
	Transaзгo de inclusгo/alteraзгo de registros
*/
include("../inc/common.php");

/*
	verificaзгo do nнvel do usuбrio, altere conforme sua necessidade, os nъmeros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");


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
if (getParam("f_categoria")=="")          $erro->addErro('Categoria deve ser informada.');

/*
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na validaзгo
	// objeto para montagem de expressгo sql
	$sql = new UpdateSQL();
	
	$sql->setTable("documento_categoria");
	$sql->setKey("cod_categoria",        getParam("f_id"),              "Number");
	
	$sql->addField("categoria",          getParam("f_categoria"),       "String");
	
	if (strlen(getParam("f_id"))>0) { // alteraзгo, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../admin/documento_categoria_lista.php"; 
	} else { // inclusгo
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../admin/documento_categoria_lista.php"; 
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