<?
include("../inc/common.php");
verificaPermissaoPagina("10,1");
$conn = new db();
$conn->open();
$dt_data   = dtos(getParam("f_dt_data"));
$publicado     = strlen(getParam("f_int_publicado"))==0?"0":getParam("f_int_publicado");

$erro = new Erro();
if (getParam("f_txt_titulo")=="")                $erro->addErro('Tнtulo deve ser informado.');
if (getParam("f_txt_texto")=="")                 $erro->addErro('Texto deve ser informado.');

$upload = new Upload();
$try = $upload->setArquivo($_FILES['f_imagem']);
if ($try != 0) {
   $upload->setDiretorio($_SERVER["DOCUMENT_ROOT"]."arquivos/");
   $upload->setLargura(5);
   $upload->setFiltro("IMAGE");
   $erroTmp = $upload->valida();
		if ($erroTmp != "") {
      $erro->addErro($erroTmp);
   }
   else {
      $upload->envia();
      $imagem = $upload->getNomeArquivo();
      if (getParam("f_imagemOld") != "") { $upload->excluir(getParam("f_imagemOld")); }
   }
}
/* 
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/
if (!$erro->hasErro()) { // passou na validaзгo
	$sql = new UpdateSQL();	
	$sql->setTable("evento");
	$sql->setKey("cod_evento",	getParam("f_id"),	"Number");
	
	$sql->addField("dt_data",	$dt_data,	"Date");
	$sql->addField("hora",	getParam("f_dt_hora"),	"String");
	$sql->addField("txt_titulo",	getParam("f_txt_titulo"),	"String");
	$sql->addField("local",	getParam("f_txt_local"),	"String");
	$sql->addField("txt_texto",		addslashes(getParam("f_txt_texto")),   "String");
	$sql->addField("int_publicado",	$publicado,	"Number");
	
	if ($imagem != "") {
	   $sql->addField("imagem",	$imagem,	"String");
	}
	
	if (strlen(getParam("f_id"))>0) { // alteraзгo, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		$conn->execute($sql->getSQL());
		$destino = "../admin/evento_lista.php?pagina=".getParam("pagina"); 
	} else { // inclusгo
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../admin/evento_lista.php"; 
	}
	
	// volta para a lista ou reapresenta o formulбrio em modo de ediзгo
	redirect($destino,"content");
} else { // nгo passou na validaзгo
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conexгo com o banco de dados
*/
echo $sql->getSQL();
$conn->close();
?>