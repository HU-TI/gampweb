<?
/*
	Modelo de p�gina que apresenta um formul�rio
	para inclus�o/altera��o de registros
*/
//include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
//verificaPermissaoPagina("10,1");


/*
	estabelece conex�o com o banco de dados
*/
//$conn = new db();
//$conn->open();

// defini��o da express�o SQL para a fun��o listbox
$sqlNivel = "SELECT cod_nivel as id, nivel as val FROM usuario_nivel ORDER BY nivel";

?>
<html>
<head>
	<title>usuario-trocar-senha-edicao</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="javascript" src="../inc/js/lookup.js"></script>
	<script language="JavaScript" src="../inc/js/focus.js"></script>
	<script language='JavaScript'>
	/*
		fun��o que chama a rotina de salvamento,
		altere somente o nome da p�gina
	*/
	function salvar() {
		//document.frm.target = "controle";
		document.frm.action = "usuario_troca_senha_salvar.php";
		document.frm.submit();
	}
	
	/*
		fun��o que define o foco inicial do formul�rio,
		altere conforme o campo do formul�rio
	*/
	function inicializa() {
		parent.content.document.frm.f_senha_atual.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
//pageTitle("Usu�rio","Troca de senha");

/*
	bot�es de a��es,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

//$button = new Button;
//$button->addItem(" Salvar ","javascript:salvar()","content");
//$button->addItem(" Salvar ","javascript:salvar()");
//echo $button->writeHTML();
echo "<br>";

/*
	Formul�rio
*/
$form = new Form("frm", "index.php?menu=trocarsenhasalvar", "POST", "", "90%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s"); // vari�vel de controle
$form->addHidden("f_id",getSession("sis_usercode")); // chave prim�ria
$form->addHidden("pagina",getParam("pagina")); // n�mero da p�gina que chamou

$form->addField("Senha atual: ",            passwordField("f_senha_atual","",20,20));
$form->addField("Nova senha: ",             passwordField("f_senha_nova", "",20,20));
$form->addField("Confirme a nova senha: ",  passwordField("f_senha_conf", "",20,20));
$form->addField("",  "<input type=submit>");
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
