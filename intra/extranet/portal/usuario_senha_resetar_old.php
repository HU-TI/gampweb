<?
$erro = new Erro();
//if(getParam("f_email")=="") $erro->addErro("Email deve ser infomado");
$lista = getparam("f_email");
echo $lista;

if ($erro->hasErro()) { // se n�o passou na valida��o...
	alert('Ocorreram os seguintes erros!\n' . $erro->toString());
	redirect("../admin/usuario_lista.php","content");
} else { // se passou na valida��o
	if (strlen($lista)==0) { // se n�o existe registros selecionados
		alert("Nenhum registro selecionado!");
	} else { // se existe registro selecionado
		$sql = new UpdateSQL();
		$sql->setTable("usuario");
		$sql->setKey("cod_usuario", $lista, "Number");
		$sqlDados = "SELECT * FROM usuario WHERE email = " . $lista;
		echo $sqlDados;
		$senha = geraSenhaMailReseta($lista,2);
		$rsDados = new query($conn, $sqlDados);
		if ($rsDados->getrow()) {
			$bd_usuario		= $rsDados->field("usuario");
			$bd_nome		= $rsDados->field("nome");
			$bd_email		= $rsDados->field("email");
			alert($bd_email.$bd_nome.$bd_usuario);
		}
		if(strlen(getDbValue("SELECT email FROM usuario WHERE email= $lista"))>0) {
			$senha = geraSenhaMailReseta($lista,2);
			alert($lista);
		}
		$sql->addField("senha", md5($senha), "String");
		$sql->camposControle("UPDATE",dbnow());
		$sql->setAction("UPDATE");
		$conn->execute($sql->getSQL());
		alert("Senha reiniciada com sucesso!");
		redirectportal("index_reseta.php");
	}
}
/*
 fecha a conex�o com o banco de dados
*/
$conn->close();
?>