<?php
$usuario= $_COOKIE['username']; 
include "valida_cookies.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<title>Cadastro de Usuario</title>
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/estilo.css" />
<link rel="stylesheet" media="print" type="text/css" href="css/print.css" />
<link rel="stylesheet" media="aural" type="text/css" href="css/aural.css" />
</head>
<body>

<?php
$usuario = $_GET['usuario'];
include "config.php";
        
		$sql = mysql_query("SELECT cod_usuario, nome, usuario, senha FROM usuario where usuario='$usuario' LIMIT 1");
        while ($sel = mysql_fetch_array($sql)){
		print $cod_usuario = $sel['cod_usuario'];
        print $nome = $sel['nome'];
        print $usuario = $sel['usuario'];
        print $senha = $sel['senha'];		
		}


?>
<div id="link_acesso">
  <p><b>Alterar Senha</b></p>
</div>
<form id="form1" name="cadastro" method="post"  action="cripto.php">
<input type="hidden" name="cod_usuario" id="cod_usuario" value = "<? echo $cod_usuario; ?>" size="11" />
<input type="hidden" name="usuario" id="usuario" value = "<? echo $usuario; ?>" size="11" />
<table align="center" border="0">
<tr>
<td><div id="link_acesso">Senha atual:</div></td>
<td><input name="senha_atual" type="password" id="senha_atual" /></td>
</tr>
<tr>
<td><div id="link_acesso">Nova senha:</div></td>
<td><input name="nova_senha" type="password" id="nova_senha" /></td>
</tr>
<tr>
<td><div id="link_acesso">Confirma senha:</div></td>
<td><input name="confirma_senha" type="password"  id="confirma_senha" /></td>
</tr>
<tr>
<td></td>
<td> <input type="submit" name="Submit" value="Salvar" /></td>
</tr>
</table>
</form>  
</body>
</html>
