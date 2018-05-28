<?
/*
	Montagem dos frames da aplicação, não deve ser alterada
*/
include("../inc/common.php");
?>
<html>
<head>
<title><?=SIS_TITULO?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset rows="<?=FRAME_HEADER_ALTURA?>,*" cols="*" frameborder="NO" border="0" framespacing="0">
	<frame src="header.php" name="header" scrolling="NO" noresize >
	<frameset rows="*" cols="<?=FRAME_MENU_LARGURA?>,*" framespacing="0" frameborder="NO" border="0">
		<frame src="menu.php" name="menu" scrolling="NO" noresize>
		<frameset rows="*,<?=FRAME_CONTROLE_ALTURA?>" framespacing="0" frameborder="NO" border="0">
			<frame src="../admin/evento_lista.php" name="content" scrolling="auto" noresize>
			<frame src="controle.php" name="controle">
		</frameset>
	</frameset>
</frameset>
<noframes><body>

</body></noframes>
</html>