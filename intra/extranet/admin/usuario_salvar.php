<?
/*
	Transa��o de inclus�o/altera��o de registros
*/
include("../inc/common.php");

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
	tratamento de campos,
	configure conforme sua necessidade,
	siga o exemplo abaixo
*/
//$data_cadastro = dtos(getParam("f_data_cadastro"));
//$ativo         = strlen(getParam("f_ativo"))==0?"0":getParam("f_ativo");
//$descricao     = addslashes(getParam("f_descricao"));

/*
	valida��o,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_usuario")=="")          $erro->addErro('Nome de usu�rio deve ser informado.');
//if (isDuplicated("usuario", "usuario", "cod_usuario", getParam("f_usuario"), getParam("f_id"))) $erro->addErro('Nome de usu�rio j� existe.');
if (!ereg(REGEX_EMAIL, getParam("f_email"))) $erro->addErro('Endere�o de e-mail inv�lido.');
/*
	Atualiza��o dos dados, configure abaixo
	conforme suas necessidades
*/

if (!$erro->hasErro()) { // passou na valida��o
	// objeto para montagem de express�o sql
	$sql = new UpdateSQL();
	$sql->setTable("usuario");
	$sql->setKey("cod_usuario",         getParam("f_id"),              "Number");	
	$sql->addField("usuario",           getParam("f_usuario"),         "String");
	$sql->addField("nivel_acesso",      getParam("f_nivel"),           "Number");
	$sql->addField("nome",              getParam("f_nome"),            "String");
	$sql->addField("email",             getParam("f_email"),           "String");
	$sql->addField("fone",             getParam("f_fone"),           "String");
	
	if (getParam("f_id")>0) { // altera��o, retirar strlen se vier de edicao_aux
		$sql->camposControle("UPDATE",dbnow());
		$sql->setAction("UPDATE");
   $conn->execute($sql->getSQL());
		$destino = "../admin/usuario_lista.php"; 
	} else { // inclus�o
   $senha = geraSenhaMail(getParam("f_nome"),getParam("f_usuario"),getParam("f_email"));
 	$sql->addField("senha",             md5($senha),                   "String");
		$sql->camposControle("INSERT",dbnow());
		$sql->setAction("INSERT");
		$last_id = $conn->execute($sql->getSQL());
   $destino = "../admin/usuario_lista.php"; 
	}
	
	// volta para a lista ou reapresenta o formul�rio em modo de edi��o
	redirect($destino,"content");
} else { // n�o passou na valida��o
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conex�o com o banco de dados
*/
$conn->close();
?>