<?
/*
 Transaзгo para exclusгo de um ou mais registros
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
 captura e prepara a lista de registros
*/ 
$lista_exclusao = getParam("sel");
if (is_array($lista_exclusao)) {
 $lista_exclusao = implode(",",$lista_exclusao);
}

/*
 validaзгo,
 coloque aqui estruturas condicionais que
 alimentam a variбvel MSG. siga o exemplo abaixo.
*/
$erro = new Erro();
$sqlQtdeCatDoc = "SELECT count(*) as qtdeCatDoc FROM documento WHERE cod_categoria IN (".$lista_exclusao.")";
if (getDbValue($sqlQtdeCatDoc)>0) $erro->addErro('Existem registros associados em Documentos.'); 



if ($erro->hasErro()) { // se nгo passou na validaзгo...
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	redirect("../admin/documento_categoria_lista.php","content");
} else { // se passou na validaзгo
	if (strlen($lista_exclusao)==0) { // se nгo existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
		// configure a expressгo SQL abaixo conforme sua necessidade
		$sql = "DELETE FROM documento_categoria WHERE cod_categoria IN (" . $lista_exclusao . ")";
		$conn->execute($sql);
		redirect("../admin/documento_categoria_lista.php","content");
	}
}
/*
 fecha a conexгo com o banco de dados
*/
$conn->close();
?>