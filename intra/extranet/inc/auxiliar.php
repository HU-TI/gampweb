<?
/*
	Janela auxiliar.
	NÃO DEVE SER ALTERADA
*/
include("../inc/common.php");

$arg = explode("?",getParam("pag"));
$pag = $arg[0];
$parm = str_replace(",","&",$arg[1]);
?>
<html>
<head>
<title>Janela Auxiliar</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<frameset rows="*,<?=FRAME_CONTROLE_ALTURA?>" framespacing="0" frameborder="NO" border="0">
	<frame src="<?=$pag?>?<?=$parm?>" name="content" scrolling="auto" noresize>
	<frame src="../common/controle.php" name="controle">
</frameset>
<noframes><body>

</body></noframes>
</html>