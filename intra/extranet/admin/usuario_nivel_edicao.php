<?
/*
	Modelo de página que apresenta um formulário
	para inclusão/alteração de registros
*/
include("../inc/common.php");

define("OBJETO","nivel");
define("OBJETO_ARQUIVO","usuario_nivel");
define("OBJETO_TABELA","usuario_nivel");
define("OBJETO_TITULO","Níveis");
define("OBJETO_TITULO_SINGULAR","Nível");
define("DIRETORIO","admin");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");


/*
	estabelece conexão com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	tratamento de campos, caso seja edição.
	configure conforme suas necessidades
*/
$id = getParam("id"); // captura a variável que veio de objeto_lista
if (strlen($id)>0) { // edição
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
		função que chama a rotina de salvamento,
		altere somente o nome da página
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../<?=DIRETORIO?>/<?=OBJETO_ARQUIVO?>_salvar.php";
		parent.content.document.frm.submit();
	}
	
	/*
		função que define o foco inicial do formulário,
		altere conforme o campo do formulário
	*/
	function inicializa() {
		parent.content.document.frm.f_<?=OBJETO?>.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
pageTitle(OBJETO,"Edição");

/*
	botões de ações,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../".DIRETORIO."/".OBJETO_ARQUIVO."_lista.php?$retorno","content");
echo $button->writeHTML();

/*
	Controle de abas,
	true, se for a aba da página atual,
	false, se for qualquer outra aba,
	configure conforme o exemplo abaixo
*/
$abas = new Abas();
$abas->addItem("Geral",true);
echo $abas->writeHTML();

echo "<br>";

/*
	Formulário
*/
$form = new Form("frm", "../".DIRETORIO."/".OBJETO_ARQUIVO."_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s"); // variável de controle
$form->addHidden("f_id",              $bd_codOBJETO); // chave primária
$form->addHidden("pagina",            getParam("pagina")); // número da página que chamou

$form->addField(OBJETO_TITULO_SINGULAR.": ",  textField("f_".OBJETO,$bd_OBJETO,20,20));
echo $form->writeHTML();
?>
</body>
</html>
<?
/*
	encerra a conexão com o banco de dados
*/
$conn->close();
?>
