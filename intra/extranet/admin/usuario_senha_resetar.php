<?
/*
 Transa��o para exclus�o de um ou mais registros
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
 captura e prepara a lista de registros
*/ 

/*
 valida��o,
 coloque aqui estruturas condicionais que
 alimentam a vari�vel MSG. siga o exemplo abaixo.
*/
$erro = new Erro();

$lista = getParam("sel");
if (sizeof($lista) != 1) $erro->addErro('Apenas um usu�rio deve ser selecionado.');
$lista = $lista[0];

if ($erro->hasErro()) { // se n�o passou na valida��o...
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	redirect("../admin/usuario_lista.php","content");
} else { // se passou na valida��o
	if (strlen($lista)==0) { // se n�o existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
	   $sql = new UpdateSQL();
	   $sql->setTable("usuario");
	   $sql->setKey("cod_usuario",         $lista,              "Number");
	   $sqlDados = "select * from usuario where cod_usuario = " . $lista;
		 $rsDados = new query($conn, $sqlDados);
	   if ($rsDados->getrow()) {
		    $bd_usuario         = $rsDados->field("usuario");
		    $bd_nome            = $rsDados->field("nome");
		    $bd_email           = $rsDados->field("email");
	   }
		 $senha = geraSenhaMail($bd_nome,$bd_usuario,$bd_email,2);
	   $sql->addField("senha",             md5($senha),                   "String");
		 $sql->camposControle("UPDATE",dbnow());
		 $sql->setAction("UPDATE");
    $conn->execute($sql->getSQL());
		 alert("Senha reiniciada com sucesso!");
 	 redirect("../admin/usuario_lista.php","content");
	}
}
/*
 fecha a conex�o com o banco de dados
*/
$conn->close();
?>