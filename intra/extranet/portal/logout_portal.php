<?
/*
	Esta transaзгo destrуi a sessгo do usuбrio e exibe
	o formulбrio de login
*/
setSession("sis_username","");
setSession("sis_usercode","");
setSession("sis_level","");
setSession("sis_apl","");
unset($_SESSION["sis_username"]);
unset($_SESSION["sis_usercode"]);
unset($_SESSION["sis_level"]);
unset($_SESSION["sis_apl"]);
redirectportal("index.php");
?>