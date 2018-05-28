<?
/*
	Esta transação valida os dados informados no formulário de login.
	Não precisa ser alterada
*/
include("../inc/common.php");

/*
	conexão com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	dados sobre a tabela de autenticação
*/
$auth_tabela   = AUTH_TABELA;
$auth_id       = AUTH_ID;
$auth_username = AUTH_USERNAME;
$auth_password = AUTH_PASSWORD;
$auth_level    = AUTH_LEVEL;
$auth_cript    = AUTH_CRIPT;

/*
	tratamento de campos
*/
$fusername = getParam("f_username");
if ($auth_cript) {
	$fsenha = md5(getParam("f_senha"));
} else {
	$fsenha = getParam("f_senha");
}

/*
	controle de fluxo de página
*/
$ret_page = getParam("ret_page");
$querystring = getParam("querystring");
if ($ret_page=="") $ret_page="../common/content.php";

/*
	validação
*/
$erro = new Erro();
if ($fusername == "") $erro->addErro('Nome de usuário deve ser informado.');
if ($fsenha == "") $erro->addErro('Senha deve ser informada.');

// se passou na validação...
if (!$erro->hasErro()) { 
	$sql = "SELECT * FROM $auth_tabela WHERE $auth_username='$fusername' AND $auth_password = '$fsenha'";
	$rs = new query($conn, $sql);
	// se entrou...
	if ($rs->getrow()) { 
		setSession("sis_username",$rs->field("$auth_username"));
		setSession("sis_usercode",$rs->field("$auth_id"));
		setSession("sis_username_antigo",$rs->field("$auth_username"));
		setSession("sis_level",$rs->field("$auth_level"));
		setSession("sis_apl", SIS_APL_NAME);
		$destino = $ret_page;
	// se não entrou...
	} else {
		alert("Nome de usuário ou senha não conferem!");
		$destino = "../common/login.php";
	}
// não passou na validação...
} else { 
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	$destino = "../common/login.php";
}
echo "<script language='JavaScript'>";
echo "top.header.document.location.reload();";
echo "top.menu.document.location.reload();";
echo "</script>";
$conn->close();
redirect($destino);
?>