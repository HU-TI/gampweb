<?
/*
	Modelo de p�gina que apresenta um formul�rio
	para inclus�o/altera��o de registros
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
	estabelece conex�o com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	tratamento de campos, caso seja edi��o.
	configure conforme suas necessidades
*/
$id = getParam("id"); // captura a vari�vel que veio de objeto_lista
if (strlen($id)>0) { // edi��o
	$sql = "SELECT * FROM ".OBJETO_TABELA." WHERE cod_".OBJETO."=" . $id;
	$rs = new query($conn, $sql);
	if ($rs->getrow()) {
		$bd_cod_OBJETO            = $rs->field("cod_".OBJETO);
		$bd_OBJETO                = $rs->field(OBJETO);
	}
}

?>
<html>
<head>
	<title><?=OBJETO_SINGULAR?>-edicao</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="JavaScript" src="../inc/js/focus.js"></script>
	<script language="JavaScript" src="../inc/js/textcounter.js"></script>
	<script language='JavaScript'>
	/*
		fun��o que chama a rotina de salvamento,
		altere somente o nome da p�gina
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../<?=DIRETORIO?>/<?=OBJETO_ARQUIVO?>_salvar.php";
		parent.content.document.frm.submit();
	}
	
	/*
		fun��o que define o foco inicial do formul�rio,
		altere conforme o campo do formul�rio
	*/
	function inicializa() {
		parent.content.document.frm.f_<?=OBJETO?>.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
pageTitle(OBJETO,"Edi��o");

/*
	bot�es de a��es,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php?$retorno","content");
echo $button->writeHTML();

/*
	Controle de abas,
	true, se for a aba da p�gina atual,
	false, se for qualquer outra aba,
	configure conforme o exemplo abaixo
*/
$abas = new Abas();
$abas->addItem("Geral",true);
echo $abas->writeHTML();

echo "<br>";

/*
	Formul�rio
*/
$form = new Form("frm", "../".DIRETORIO."/".OBJETO_ARQUIVO."_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s"); // vari�vel de controle
$form->addHidden("f_id",              $bd_codOBJETO); // chave prim�ria
$form->addHidden("pagina",            getParam("pagina")); // n�mero da p�gina que chamou

$form->addField(OBJETO_TITULO_SINGULAR.": ",  textField("f_".OBJETO,$bd_OBJETO,20,20));
echo $form->writeHTML();
?>
</body>
</html>
<?
/*
	encerra a conex�o com o banco de dados
*/
$conn->close();
?>
