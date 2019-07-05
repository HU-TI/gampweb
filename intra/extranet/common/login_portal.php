<?
/*
	Esta página exibe o formulário de login
*/
//include("../inc/common.php");

$ret_page = getParam("ret_page");
$querystring = getParam("querystring");
if (!$ret_page) {
	$label_botao = "Entrar";
} else {
	$label_botao = "Continuar";
}

$button = new Button;
$button->addItem(" Retornar ","JavaScript:history.go(-2)");
$button->addItem($label_botao,"JavaScript:document.frm.submit()");

$form = new Form("frm", "index.php?menu=login_validar_portal", "POST", "controle", "300");
$form->setLabelWidth("50%");
$form->setDataWidth("50%");

$form->addHidden("rodou","s");
$form->addHidden("ret_page",$ret_page);
$form->addHidden("querystring",$querystring);

$form->addField("Nome do usuário:", textField("f_username",getSession("sis_username_antigo"),20,50));
$form->addField("Senha:",passwordField("f_senha","",20,50));
$form->addBreak($button->writeHTML(),false);
?>
<html>
<head>
	<title>login</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="JavaScript">
	function inicializa() {
		if (document.captureEvents && Event.KEYUP) {
			document.captureEvents( Event.KEYUP);
		}
		document.onkeyup = trataEvent;
		
		// inicia o foco no primeiro campo
		user = document.frm.f_username.value;
		if (user.length > 0) {
			document.frm.f_senha.focus();
		} else {
			document.frm.f_username.focus();
		}
	}
	
	// tratamento para capturar tecla enter
	function trataEvent(e) {
		//verifica se é IE
		if( !e ) {
			if( window.event ) {
				e = window.event;
			} else {
				//falha, não tem como capturar o evento
				return;
			}
		}
		if( typeof( e.keyCode ) == 'number'  ) {
			//IE, NS 6+, Mozilla 0.9+
			e = e.keyCode;
		} else {
			//falha, não tem como obter o código da tecla
			return;
		}
		if (e==13) {
			document.frm.submit();
		}
	}
	</script
</head>
<body class="contentBODY" onload="inicializa()">
<br><br>
<p align="center" class="titulo"><?=LOGIN_TITULO?></p>
<br>
<?
echo $form->writeHTML();
?>
<br><br>
<p align="center" class="subtitulo"></p>
</body>
</html>