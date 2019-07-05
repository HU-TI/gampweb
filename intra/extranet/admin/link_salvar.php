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
$descricao     = addslashes(getParam("f_descricao"));

/*
	validaзгo,
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
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na validaзгo
	// objeto para montagem de expressгo sql
	$sql = new UpdateSQL();
	
	$sql->setTable("link");
	$sql->setKey("cod_link",             getParam("f_id"),              "Number");
	
	$sql->addField("titulo",             getParam("f_titulo"),          "String");
	$sql->addField("url",                getParam("f_url"),             "String");
	$sql->addField("descricao",          $descricao,                    "String");
	$sql->addField("cod_categoria",      getParam("f_cod_categoria"),   "Number");
	
	if (strlen(getParam("f_id"))>0) { // alteraзгo, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../admin/link_lista.php"; 
	} else { // inclusгo
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../admin/link_lista.php"; 
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