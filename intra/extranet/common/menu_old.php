<?
/*
	Esta página apresenta o menu do sistema com base nas configurações do
	arquivo menu.inc.php, e pode ser substituido por qualquer outro mecanismo
	de menu. Na dúvida, não toque em nada.
*/
include("../inc/common.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Menu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_MENU?>">
	<script language="JavaScript">
		if (document.getElementById){
			document.write('<style type="text/css">\n')
			document.write('.submenu{display: none;}\n')
			document.write('</style>\n')
		}
		
		function SwitchMenu(obj){
			if(document.getElementById){
				var el = document.getElementById(obj);
				var ar = document.getElementById("masterdiv").getElementsByTagName("span");
				if(el.style.display != "block"){
					for (var i=0; i<ar.length; i++){
						if (ar[i].className=="submenu")
							ar[i].style.display = "none";
					}
					el.style.display = "block";
				}else{
					el.style.display = "none";
				}
			}
		}

	</script>
</head>
<?
function isValidRow($l) {
	$retorno = true;
	if (substr($l,0,1)=="#") $retorno = false;
	if (strlen(trim($l))==0) $retorno = false;
	return $retorno;
}

// lê dados do arquivo menu.inc
$arquivo = "../inc/menu.inc.php";
$ponteiro = fopen($arquivo, "r");
$conteudo = fread($ponteiro, filesize($arquivo));
fclose($ponteiro);

// cria linhas
$linha = explode("\n", $conteudo);

// agrupando os módulos
for($i = 0; $i < sizeof($linha); $i++) {
	if (isValidRow($linha[$i])) {
		$parte = explode("|", $linha[$i]);
		if ((!isset($array_modulo))||(!in_array($parte[0], $array_modulo))) {
			$array_modulo[] = $parte[0];
			$array_titulo_modulo[] = $parte[1];
		}
	}
}

// agrupando itens de menu
for($i = 0; $i <= sizeof($linha); $i++) {
	if (isValidRow($linha[$i])) {
		$parte = explode("|", $linha[$i]);
		$modulos[] = trim($parte[0]);
		$titulo_modulos[] = trim($parte[1]);
		$item[] = trim($parte[2]);
		$url[] = trim($parte[3]);
		$target[] = trim($parte[4]);
		$level[] = trim($parte[5]);
	}
}
?>
<body class="menuBODY">

<div id="masterdiv" class="menuTABLE">
	<?
	for ($i=0; $i<sizeof($array_modulo); $i++) {
		$q=0;
		for ($x=0; $x<sizeof($item); $x++) {
			if ($modulos[$x]==$array_modulo[$i]) {
				if (isValidUser($level[$x])) $q++;
			}
		}
		if ($q > 0) {
	?>
	<div class="modulo" onclick="SwitchMenu('sub<?=$i?>')"><?=$array_titulo_modulo[$i]?></div>
	<span class="submenu" id="sub<?=$i?>">
		<table border="0" cellpadding="0" cellspacing="0" width="100%">
			<?
			$c=0;
			for ($x=0; $x<sizeof($item); $x++) {
				if ($modulos[$x]==$array_modulo[$i]) {
					if (isValidUser($level[$x])) {
			?>
			<tr><td valign="top">&nbsp;&nbsp;</td><td class="menuTD"><a class="menu" href="<?=$url[$x]?>" target="<?=$target[$x]?>"><?=$item[$x]?></a></td></tr>
			<?
						$c++;
					}
				}
			}
			?>
		</table>
		<br>
	</span>
	<?
		}
	}
	?>
</div>
</body>
</html>