<?
/*
	Esta transa��o destr�i a sess�o do usu�rio e exibe
	o formul�rio de login
*/
include("../inc/common.php");
setSession("sis_username","");
setSession("sis_usercode","");
setSession("sis_level","");
setSession("sis_apl","");
unset($_SESSION["sis_username"]);
unset($_SESSION["sis_usercode"]);
unset($_SESSION["sis_level"]);
unset($_SESSION["sis_apl"]);
redirect("../common/index.php","top");
?>