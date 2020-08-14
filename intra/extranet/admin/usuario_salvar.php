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
	tratamento de campos,
	configure conforme sua necessidade,
	siga o exemplo abaixo
*/
//$data_cadastro = dtos(getParam("f_data_cadastro"));
//$ativo         = strlen(getParam("f_ativo"))==0?"0":getParam("f_ativo");
//$descricao     = addslashes(getParam("f_descricao"));

/*
	validaзгo,
	coloque aqui estruturas condicionais que
	alimentem o objeto Erro. siga o exemplo abaixo.
*/
$erro = new Erro();
if (getParam("f_usuario")=="")          $erro->addErro('Nome de usuбrio deve ser informado.');
//if (isDuplicated("usuario", "usuario", "cod_usuario", getParam("f_usuario"), getParam("f_id"))) $erro->addErro('Nome de usuбrio jб existe.');
if (!ereg(REGEX_EMAIL, getParam("f_email"))) $erro->addErro('Endereзo de e-mail invбlido.');
/*
	Atualizaзгo dos dados, configure abaixo
	conforme suas necessidades
*/

if (!$erro->hasErro()) { // passou na validaзгo
	// objeto para montagem de expressгo sql
	$sql = new UpdateSQL();
	$sql->setTable("usuario");
	$sql->setKey("cod_usuario",         getParam("f_id"),              "Number");	
	$sql->addField("usuario",           getParam("f_usuario"),         "String");
	$sql->addField("nivel_acesso",      getParam("f_nivel"),           "Number");
	$sql->addField("nome",              getParam("f_nome"),            "String");
	$sql->addField("email",             getParam("f_email"),           "String");
	$sql->addField("fone",             getParam("f_fone"),           "String");
	
	if (getParam("f_id")>0) { // alteraзгo, retirar strlen se vier de edicao_aux
		$sql->camposControle("UPDATE",dbnow());
		$sql->setAction("UPDATE");
   $conn->execute($sql->getSQL());
		$destino = "../admin/usuario_lista.php"; 
	} else { // inclusгo
   $senha = geraSenhaMail(getParam("f_nome"),getParam("f_usuario"),getParam("f_email"));
 	$sql->addField("senha",             md5($senha),                   "String");
		$sql->camposControle("INSERT",dbnow());
		$sql->setAction("INSERT");
		$last_id = $conn->execute($sql->getSQL());
   $destino = "../admin/usuario_lista.php"; 
	}
	
	// volta para a lista ou reapresenta o formulбrio em modo de ediзгo
	redirect($destino,"content");
} else { // nгo passou na validaзгo
	alert('Ocorreram os seguintes erros!\n\n'.$erro->toString());
}
/*
	Encerra a conexгo com o banco de dados
*/
$conn->close();
?>