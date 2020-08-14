<?
$destino = "index.php"; 
/*
	validaчуo,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_senha_atual")=="")                 $erro->addErro('Informe a senha atual.');
$sql = "select senha from usuario where cod_usuario = ".getSession("sis_usercode");
$senhaBanco = getDbValue($sql);
if (md5(getParam("f_senha_atual"))!= $senhaBanco) $erro->addErro('A senha atual digitada estс incorreta.');
if (getParam("f_senha_nova")=="")                  $erro->addErro('Informe a nova senha.');
if (getParam("f_senha_conf")=="")                  $erro->addErro('Informe a confirmaчуo da nova senha.');
if(getParam("f_senha_conf") != getParam("f_senha_nova")) $erro->addErro('Nova senha e confirmaчуo devem ser iguais.');

/*
	Atualizaчуo dos dados, configure abaixo
	conforme suas necessidades
*/

if (!$erro->hasErro()) { // passou na validaчуo
	// objeto para montagem de expressуo sql
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
else { // nуo passou na validaчуo
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
	$destino ="index.php?menu=trocarsenha";
	redirectportal($destino,"");
}
/*
	Encerra a conexуo com o banco de dados
*/
$conn->close();
?>