<?
/*
 Transa��o para exclus�o de um ou mais registros
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
 captura e prepara a lista de registros
*/ 
$lista_exclusao = getParam("sel");
$lista          = getParam("sel");
if (is_array($lista_exclusao)) {
 $lista_exclusao = implode(",",$lista_exclusao);
}

/*
 valida��o,
 coloque aqui estruturas condicionais que
 alimentam a vari�vel MSG. siga o exemplo abaixo.
*/
$erro = new Erro();
//$sqlQtdeSistUsu = "SELECT count(*) as qtdeSistUsu FROM sistema_usuario WHERE usuario_id IN (".$lista_exclusao.")";
//if (getDbValue($sqlQtdeSistUsu)>0) $erro->addErro('Existem registros associados em Sistemas do Usu�rio.'); 
//$sqlQtdeHist = "SELECT count(*) as qtdeHist FROM historico WHERE usuario_id IN (".$lista_exclusao.")";
//if (getDbValue($sqlQtdeHist)>0) $erro->addErro('Existem registros associados em Hist�rico.');



if ($erro->hasErro()) { // se n�o passou na valida��o...
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	redirect("../admin/evento_lista.php","content");
} else { // se passou na valida��o
	if (strlen($lista_exclusao)==0) { // se n�o existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
		$upload = new Upload();
		$upload->setDiretorio($_SERVER["DOCUMENT_ROOT"]."arquivos/");
		for($i=0;$i<sizeof($lista);$i++){
		   $cod = $lista[$i];
			 $arquivo = getDbValue("select imagem from evento where cod_evento = $cod");
		   $upload->excluir($arquivo);   
		}
		$sql = "DELETE FROM evento WHERE cod_evento IN (" . $lista_exclusao . ")";
		$conn->execute($sql);
		redirect("../admin/evento_lista.php","content");
	}
}
/*
 fecha a conex�o com o banco de dados
*/
$conn->close();
?>