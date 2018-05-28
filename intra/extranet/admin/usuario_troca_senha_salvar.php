<?
/*
	Transaзгo de inclusгo/alteraзгo de registros
*/
include("../inc/common.php");

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
	validaзгo,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_senha_atual")=="")                 $erro->addErro('Informe a senha atual.');
$sql = "select senha from usuario where cod_usuario = ".getSession("sis_usercode");
$senhaBanco = getDbValue($sql);
if (md5(getParam("f_senha_atual"))!= $senhaBanco) $erro->addErro('A senha atual digitada estб incorreta.');
if (getParam("f_senha_nova")=="")                  $erro->addErro('Informe a nova senha.');
if (getParam("f_senha_conf")=="")                  $erro->addErro('Informe a confirmaзгo da nova senha.');
if(getParam("f_senha_conf") != getParam("f_senha_nova")) $erro->addErro('Nova senha e confirmaзгo devem ser iguais.');

/*
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/

if (!$erro->hasErro()) { // passou na validaзгo
	// objeto para montagem de expressгo sql
	$sql = new UpdateSQL();
	$sql->setTable("usuario");
	$sql->setKey("cod_usuario",         getParam("f_id"),              "Number");
	
	$sql->addField("senha",             md5(getParam("f_senha_nova")),  "String");
	$sql->camposControle("UPDATE",dbnow());
	$sql->setAction("UPDATE");
 $conn->execute($sql->getSQL());
	$destino = "../admin/usuario_troca_senha_edicao.php"; 

	alert('Senha alterada com sucesso.\n\n');
	redirect($destino,"content");
} 
else { // nгo passou na validaзгo
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conexгo com o banco de dados
*/
$conn->close();
?>