<?php
$username = $_COOKIE['username'];
$senha = $_COOKIE['senha'];

if( (!empty($username)) AND (!empty($senha)) )
{
	include "config.php";
	$sql ="SELECT * FROM usuario where usuario='$username' LIMIT 1";
	$resultado = @mysql_query($sql, $con);

	if(@mysql_num_rows($resultado)==1)
	{
		if($username!=@mysql_result($resultado,0,"username"))
		{
			if($senha!=@mysql_result($resultado,0,"senha"))
			{
				@setcookie("username",$username,time()+3600); @setcookie("senha",$senha,time()+3600);
				print "Você não efetuou o login. username e senha errados <a href=../index.php> Logar </a>"; exit;
			}
		}
	}
	else
	{
		@setcookie("username",$username,time()+3600); @setcookie("senha",$senha,time()+3600);
		print "Você não efetuou o login. - 1 <a href=index.php> Logar </a><meta http-equiv='refresh' content='2;URL=../index.php'>";
		exit;
	}
}
else
{
	print "Você não efetuou o login. - 2 <a href=../home/index.php> Logar </a><meta http-equiv='refresh' content='2;URL=../index.php'>";
	exit;
}
@mysql_close($con);
?>
