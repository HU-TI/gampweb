<?
/*
	Customize esta página de acordo com o layout gráfico que você deseja.
	A altura pode ser alterada no arquivo de configuração (config.inc.php)
*/
include("../inc/common.php");
if (strlen(getSession("sis_username"))==0) {
	$usuario_logado = "Usuário <b>ANÔNIMO</b>";
	$link = "&nbsp;|&nbsp;</font><a href='../common/login.php' target='content' class='navegacao'>LOGIN</a>";
} else {
	$usuario_logado = "Usuário: <b>" . getSession("sis_username") . "</b>";
	$link = "&nbsp;|&nbsp;</font><a href='../common/logout.php' class='navegacao'>SAIR</a>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Header</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<META HTTP-EQUIV="Refresh" CONTENT="1800;../common/header.php">
<link rel="stylesheet" type="text/css" href="<?=CSS_HEADER?>">
</head>

<body class="headerBODY">
<table width="100%" border="0" cellspacing="0" cellpadding="1">
	<tr>
		<td class="sistema"><?=SIS_TITULO?></td>
		<td>
			<div align="right">
			<font class="text"><?=$usuario_logado?>&nbsp;|&nbsp;</font>
			<font class="text">Data: <?=date("d.m.Y")?><?=$link?>
			</div>
		</td>
	</tr>
</table>
</body>
</html>