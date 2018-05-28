<?
/*
 Transa��o para exclus�o de um ou mais registros
*/
include("../inc/common.php");

define("OBJETO","nivel");
define("OBJETO_ARQUIVO","usuario_nivel");
define("OBJETO_TABELA","usuario_nivel");
define("OBJETO_TITULO","N�veis");
define("OBJETO_TITULO_SINGULAR","N�vel");
define("DIRETORIO","admin");

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
	redirect("../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php","content");
} else { // se passou na valida��o
	if (strlen($lista_exclusao)==0) { // se n�o existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
		// configure a express�o SQL abaixo conforme sua necessidade
		$sql = "DELETE FROM ".OBJETO_TABELA." WHERE cod_".OBJETO." IN (" . $lista_exclusao . ")";
		$conn->execute($sql);
		redirect("../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php","content");
	}
}
/*
 fecha a conex�o com o banco de dados
*/
$conn->close();
?>