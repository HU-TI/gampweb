<?
/*
	Modelo de página que apresenta um formulário
	para inclusão/alteração de registros
*/
//include("../inc/common.php");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
*/
//verificaPermissaoPagina("10,1");


/*
	estabelece conexão com o banco de dados
*/
//$conn = new db();
//$conn->open();

// definição da expressão SQL para a função listbox
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
		função que chama a rotina de salvamento,
		altere somente o nome da página
	*/
	function salvar() {
		//document.frm.target = "controle";
		document.frm.action = "usuario_troca_senha_salvar.php";
		document.frm.submit();
	}
	
	/*
		função que define o foco inicial do formulário,
		altere conforme o campo do formulário
	*/
	function inicializa() {
		parent.content.document.frm.f_senha_atual.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
//pageTitle("Usuário","Troca de senha");

/*
	botões de ações,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

//$button = new Button;
//$button->addItem(" Salvar ","javascript:salvar()","content");
//$button->addItem(" Salvar ","javascript:salvar()");
//echo $button->writeHTML();
echo "<br>";

/*
	Formulário
*/
$form = new Form("frm", "index.php?menu=trocarsenhasalvar", "POST", "", "90%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s"); // variável de controle
$form->addHidden("f_id",getSession("sis_usercode")); // chave primária
$form->addHidden("pagina",getParam("pagina")); // número da página que chamou

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
	encerra a conexão com o banco de dados
*/
$conn->close();
?>
