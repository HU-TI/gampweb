<?
$destino = "index.php"; 
/*
	valida��o,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_senha_atual")=="")                 $erro->addErro('Informe a senha atual.');
$sql = "select senha from usuario where cod_usuario = ".getSession("sis_usercode");
$senhaBanco = getDbValue($sql);
if (md5(getParam("f_senha_atual"))!= $senhaBanco) $erro->addErro('A senha atual digitada est� incorreta.');
if (getParam("f_senha_nova")=="")                  $erro->addErro('Informe a nova senha.');
if (getParam("f_senha_conf")=="")                  $erro->addErro('Informe a confirma��o da nova senha.');
if(getParam("f_senha_conf") != getParam("f_senha_nova")) $erro->addErro('Nova senha e confirma��o devem ser iguais.');

/*
	Atualiza��o dos dados, configure abaixo
	conforme suas necessidades
*/

if (!$erro->hasErro()) { // passou na valida��o
	// objeto para montagem de express�o sql
	$sql = new UpdateSQL();
	$sql->setTable("usuario");
	$sql->setKey("cod_usuario",         getParam("f_id"),              "Number");
	
	$sql->addField("senha",             md5(getParam("f_senha_nova")),  "String");
	$sql->camposControle("UPDATE",dbnow());
	$sql->setAction("UPDATE");
 $conn->execute($sql->getSQL());
	

	alert('Senha alterada com sucesso.\n\n');
	redirectportal($destino,"");
} 
else { // n�o passou na valida��o
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
	$destino ="index.php?menu=trocarsenha";
	redirectportal($destino,"");
}
/*
	Encerra a conex�o com o banco de dados
*/
$conn->close();
?>