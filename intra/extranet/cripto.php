<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Aletrar senha extranet Reequilibrio</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" media="screen,projection" type="text/css" href="css/estilo.css" />
<link rel="stylesheet" media="print" type="text/css" href="css/print.css" />
<link rel="stylesheet" media="aural" type="text/css" href="css/aural.css" />
<script type='text/javascript'>
<!--
function FecharJanela() 
{
ww = window.open(window.location, "_self");
ww.close();
} 
-->
</script>
</head>
<body>

<?php
include ("config.php");
$cod_usuario = $_POST['cod_usuario'];
$usuario = $_POST['usuario'];
$senha = $_POST['senha_atual'];
$nova_senha = $_POST['nova_senha'];
$confirma_senha = $_POST['confirma_senha'];
$senha  = md5($senha);

if($usuario != ''){	

		$sql = mysql_query("SELECT cod_usuario, nome, usuario, senha FROM usuario where usuario='$usuario' LIMIT 1");
        while ($sel = mysql_fetch_array($sql)){
		$cod_usuario = $sel['cod_usuario'];
		$usuario = $sel['usuario'];
		$nome = $sel['nome'];
        $senha_atual = $sel['senha'];		
		}
		  
		if ($senha != $senha_atual){
	echo "<script type=\"text/javascript\">window.alert(\"Senha atual não confere!\")</script>
				<script>window.location='javascript:window.history.go(-1)';</script>";				

		}else{
				
		if (($nova_senha != $confirma_senha)or($nova_senha == '')or ($confirma_senha =='')){
	echo "<script type=\"text/javascript\">window.alert(\"Senha e confirma senha diferente ou senha em branco!\")</script>
				<script>window.location='javascript:window.history.go(-1)';</script>";		
		}else{
		//echo "Cadastro realizado com sucesso!";		
					//$usuario  = $_POST['ds_usuario'];  Recebe a veriavel nome do metodo POST
					$nova_senha  = md5($nova_senha);/*$_POST['ds_senha']);*/ // // Recebe a veriavel senha do metodo POST e encripta ela
					$sql = mysql_query("UPDATE usuario SET senha = '$nova_senha' WHERE cod_usuario = '$cod_usuario' LIMIT 1 ") 
					or die("ERRO no comando SQL : ".mysql_error()); //insere os dados na MySQL
					//echo "Usuário cadastrado"; // Exibe a mensagem Usuário cadastrado*/
					echo'<form name="form1" method="post" a action="#">
					<div id="link_acesso">Senha aletrada com sucesso!</div> 
					<input type="button" value="Ok" onclick="FecharJanela();" />
					</form>';					
		}
		}
		} 
?>
</body>
</html>