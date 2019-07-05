<?
/*
	Transa��o de inclus�o/altera��o de registros
*/
include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1,2");


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
$data = dtos(getParam("f_data"));
//$ativo         = strlen(getParam("f_ativo"))==0?"0":getParam("f_ativo");
$descricao     = addslashes(getParam("f_descricao"));

/*
	valida��o,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
echo $_SERVER["DOCUMENT_ROOT"]."/extranet/arquivos/";
$erro = new Erro();
if (getParam("f_titulo"==""))          $erro->addErro('Titulo deve ser informado.');
$upload = new Upload();

$try = $upload->setArquivo($_FILES['f_arquivo']);
//$upload->setFiltro("PACTO");
if ($try != 0) {
   $upload->setDiretorio($_SERVER["DOCUMENT_ROOT"]."/extranet/arquivos/");
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
	
	$sql->setTable("documento");
	$sql->setKey("cod_documento",getParam("f_id"),"Number");
	$sql->addField("titulo",getParam("f_titulo"),"String");
	$sql->addField("data",$data,"Date");
	if ($arquivo != "") {
	   $sql->addField(" documento",$arquivo,"String");
	}	
	$sql->addField("descricao",$descricao,"String");
	$sql->addField("cod_categoria",getParam("f_cod_categoria"),"Number");
	
	if (strlen(getParam("f_id"))>0) { // altera��o, retirar strlen se vier de edicao_aux
		$sql->setAction("UPDATE");
   $sql->camposControle("UPDATE",dbnow());
		echo $sql->getSQL();
		$conn->execute($sql->getSQL());
		$destino = "../admin/documento_lista.php"; 
	} else { // inclus�o
		$sql->setAction("INSERT");
		$sql->camposControle("INSERT",dbnow());
				echo $sql->getSQL();
		$last_id = $conn->execute($sql->getSQL());
		$destino = "../admin/documento_lista.php"; 
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
echo $sql->getSQL();
?>