<?
/*
	Transa��o de inclus�o/altera��o de registros
*/
include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");


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
$descricao     = addslashes(getParam("f_descricao"));

/*
	valida��o,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_titulo"==""))          $erro->addErro('Titulo deve ser informado.');
if (getParam("f_url")=="http://") {
	$erro->addErro('A URL  deve ser informada.\n');
}
else { 
	$txt_link = getParam("f_url");
	$url_p1 = substr($txt_link,0,7);
	if ($url_p1 != "http://") {
		$erro->addErro('URL incorreta, acrescente "http://" no inicio da URL.\n');
	}
}

$upload = new Upload();

$try = $upload->setArquivo($_FILES['f_arquivo']);
//$upload->setFiltro("PACTO");
if ($try != 0) {
   $upload->setDiretorio($_SERVER["DOCUMENT_ROOT"]."arquivos/");
   $erroTmp = $upload->valida();
   if ($erroTmp != "") {
      $erro->addErro($erroTmp);
   }
   else {
      $upload->envia();
      $arquivo = $upload->getNomeArquivo();
      if (getParam("f_arquivoOld") != "") { $upload->excluir(getParam("f_arquivoOld")); }
   }
}

/*
	Atualiza��o dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na valida��o
	// objeto para montagem de express�o sql
	$sql = new UpdateSQL();
	
	$sql->setTable("link");
	$sql->setKey("cod_link",             getParam("f_id"),              "Number");
	
	$sql->addField("titulo",             getParam("f_titulo"),          "String");
	$sql->addField("url",                getParam("f_url"),             "String");
	$sql->addField("descricao",          $descricao,                    "String");
	$sql->addField("cod_categoria",      getParam("f_cod_categoria"),   "Number");
	
	if (strlen(getParam("f_id"))>0) { // altera��o, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../admin/link_lista.php"; 
	} else { // inclus�o
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../admin/link_lista.php"; 
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