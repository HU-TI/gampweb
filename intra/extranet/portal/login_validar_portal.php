<?
/*
	Esta transa��o valida os dados informados no formul�rio de login.
	N�o precisa ser alterada
*/

/*
	dados sobre a tabela de autentica��o
*/
$auth_tabela   = AUTH_TABELA;
$auth_id       = AUTH_ID;
$auth_username = AUTH_USERNAME;
$auth_password = AUTH_PASSWORD;
$auth_level    = AUTH_LEVEL;
$auth_cript    = AUTH_CRIPT;
/*echo "<hr>";
echo $auth_tabela;
echo $auth_id;
echo $auth_username;
echo $auth_password;
echo $auth_level;
echo $auth_cript;
echo "<hr>";*/


/*
	tratamento de campos
*/
//alert(getParam("f_username"));
//alert(getParam("f_senha"));

$fusername = getParam("f_username");
if ($auth_cript) {
	$fsenha = md5(getParam("f_senha"));
} else {
	$fsenha = getParam("f_senha");
}

$sql = "SELECT * FROM ".$auth_tabela." WHERE ".$auth_username."='".$fusername."' AND ".$auth_password." = '".$fsenha."'";
//echo $sql;
//die("parou aqui");

/*
	controle de fluxo de p�gina
*/
$ret_page = getParam("ret_page");
$querystring = getParam("querystring");
//if ($ret_page=="") $ret_page="../common/content.php";
if ($ret_page=="") $ret_page="index.php";

/*
	valida��o
*/
$erro = new Erro();
if ($fusername == "") $erro->addErro('Nome de usu�rio deve ser informado.');
if ($fsenha == "") $erro->addErro('Senha deve ser informada.');

// se passou na valida��o...
if (!$erro->hasErro()) { 
	$sql = "SELECT * FROM $auth_tabela WHERE $auth_username='$fusername' AND $auth_password = '$fsenha'";
	//echo $sql;
	//die();
	$rs = new query($conn, $sql);
	// se entrou...
	if ($rs->getrow()) { 
		setSession("sis_username",$rs->field("$auth_username"));
		setSession("sis_usercode",$rs->field("$auth_id"));
		setSession("sis_username_antigo",$rs->field("$auth_username"));
		setSession("sis_level",$rs->field("$auth_level"));
		setSession("sis_apl", SIS_APL_NAME);
		$destino = $ret_page;
	// se n�o entrou...
	} else {
		alert("Nome de usu�rio ou senha n�o conferem!");
		$destino = "index.php?menu=login_portal";
	}
// n�o passou na valida��o...
} else { 
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	$destino = "index.php?menu=login_portal";
}
echo "<script language='JavaScript'>";
//echo "document.location.reload();";
echo "</script>";
//$conn->close();
redirectportal($destino);
?>