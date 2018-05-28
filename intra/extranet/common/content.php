<?
/*
	Esta é a primeira página apresentada pelo sistema, pode-se colocar aqui
	a logomarca da empresa, nome do sistema, etc...
*/
include("../inc/common.php");
verificaUsuario(0);
?>
<html>
<head>
<title>Content</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
</head>

<body class="contentBODY">
<br><br>
<p align="center" class="titulo"><?=SIS_TITULO?></p>
<p align="center" class="subtitulo"><?=SIS_VERSAO?></p>
<p align="center" class="subtitulo">responsável: <a href="mailto:<?=SIS_EMAIL_RESPONSAVEL?>"><?=SIS_NOME_RESPONSAVEL?></a></p>
<br><br><br><br>

</body>
</html>