<?

/*
 captura e prepara a lista de registros
*/ 

/*
 valida��o,
 coloque aqui estruturas condicionais que
 alimentam a vari�vel MSG. siga o exemplo abaixo.
*/
//$erro = new Erro();

$lista = getParam("f_email");

//if (sizeof($lista) != 1) $erro->addErro('Apenas um usu�rio deve ser selecionado.');
//$lista = $lista[0];

//if ($erro->hasErro()) { // se n�o passou na valida��o...
	//alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	//redirect("../admin/usuario_lista.php","content");
//} else { // se passou na valida��o
	//if (strlen($lista)==0) { // se n�o existe registros selecionados
	//	alert("Nenhum registro selecionado!");
	//} else { // se existe registro selecionado
		$sql = new UpdateSQL();
		$sql->setTable("usuario");

		$bd_cod_usuario=getDbValue("SELECT cod_usuario FROM usuario WHERE email = '$lista'");

		$sql->setKey("cod_usuario",         $bd_cod_usuario,              "Number");
		$sqlDados = "select * from usuario where email = " . $lista;
		$rsDados = new query($conn, $sqlDados);
		if ($rsDados->getrow()) {
			$bd_usuario         = $rsDados->field("usuario");
			$bd_nome            = $rsDados->field("nome");
			$bd_email           = $rsDados->field("email");
		}
		$bd_nome=getDbValue("SELECT nome FROM usuario WHERE email = '$lista'");
		$bd_usuario=getDbValue("SELECT usuario FROM usuario WHERE email = '$lista'");
		
		$senha = geraSenhaMail($bd_nome,$bd_usuario,$lista,2);
		$sql->addField("senha",md5($senha),"String");
		$sql->camposControle("UPDATE",dbnow());
		$sql->setAction("UPDATE");
		$conn->execute($sql->getSQL());
		//echo $sql->getSQL();
		//var_dump($sql);
		alert("Senha reiniciada com sucesso!");
		
		redirectportal("index_reseta.php","content");
	//}
//}
/*
 fecha a conex�o com o banco de dados
*/
$conn->close();
?>