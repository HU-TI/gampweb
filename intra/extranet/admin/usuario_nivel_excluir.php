<?
/*
 Transaзгo para exclusгo de um ou mais registros
*/
include("../inc/common.php");

define("OBJETO","nivel");
define("OBJETO_ARQUIVO","usuario_nivel");
define("OBJETO_TABELA","usuario_nivel");
define("OBJETO_TITULO","Nнveis");
define("OBJETO_TITULO_SINGULAR","Nнvel");
define("DIRETORIO","admin");

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
//$sqlQtdeSistUsu = "SELECT count(*) as qtdeSistUsu FROM sistema_usuario WHERE usuario_id IN (".$lista_exclusao.")";
//if (getDbValue($sqlQtdeSistUsu)>0) $erro->addErro('Existem registros associados em Sistemas do Usuбrio.'); 
//$sqlQtdeHist = "SELECT count(*) as qtdeHist FROM historico WHERE usuario_id IN (".$lista_exclusao.")";
//if (getDbValue($sqlQtdeHist)>0) $erro->addErro('Existem registros associados em Histуrico.');



if ($erro->hasErro()) { // se nгo passou na validaзгo...
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	redirect("../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php","content");
} else { // se passou na validaзгo
	if (strlen($lista_exclusao)==0) { // se nгo existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
		// configure a expressгo SQL abaixo conforme sua necessidade
		$sql = "DELETE FROM ".OBJETO_TABELA." WHERE cod_".OBJETO." IN (" . $lista_exclusao . ")";
		$conn->execute($sql);
		redirect("../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php","content");
	}
}
/*
 fecha a conexгo com o banco de dados
*/
$conn->close();
?>