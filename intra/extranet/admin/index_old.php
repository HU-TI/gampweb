<?
include("../inc/config.inc.php");
if (!SIS_FULLSCREEN) {
	header("Location: ../common/index.php");
}
?>
<html>
<head><title><?=SIS_TITULO?></title></head>
<script language="JavaScript">
<!--
function pageStart() {
	document.body.scroll = "no";
	var width  = 700;
	var height = 480;
	var so = navigator.platform;
	
	var jan = window.open('../common/index.php', 'wndDiretoV3', 'width=' + width + ', height=' + height + ', toolbar=no, copyhistory=no, location=no, status=yes, menubar=no, scrollbars=no, resizable=yes, top=0, left=0');
	if (so.substring(0,5) != "Linux") {
		width  = screen.availWidth;
		height = screen.availHeight;
		jan.window.resizeTo(width, height);
		jan.focus();
	}
}
-->
</script>
<body onLoad="pageStart()">
<center>
<a href="javascript:pageStart();">Acessar o sistema</a>
</center>
</body>
</html>