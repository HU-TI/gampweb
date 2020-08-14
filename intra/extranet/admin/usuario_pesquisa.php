<?
/*
	Modelo de p�gina que apresenta um formul�rio com crit�rios
	de pesquisa, os dados fornecidos nesta p�gina ser�o
	processados na p�gina de lista.
*/
include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");

?>
<html>
<head>
	<title>objeto-pesquisa</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="JavaScript">
	/*
		fun��o que chama a p�gina de lista ou relat�rio,
		enviando os par�metros de pesquisa,
		altere somente o nome da p�gina
	*/
	function pesquisar() {
		parent.content.document.frm.target = "content";
		parent.content.document.frm.action = "../admin/usuario_lista.php";
		parent.content.document.frm.submit();
	}
	
	/*
		fun��o que define o foco inicial do formul�rio
	*/
	function inicializa() {
		if (document.captureEvents && Event.KEYUP) {
			document.captureEvents( Event.KEYUP);
		}
		document.onkeyup = trataEvent;

		// inicia o foco no primeiro campo
		parent.content.document.frm.pesq_usuario.focus();
	}

	/*
		tratamento para capturar tecla enter
	*/
	function trataEvent(e) {
		if( !e ) { //verifica se � IE
			if( window.event ) {
				e = window.event;
			} else {
				//falha, n�o tem como capturar o evento
				return;
			}
		}
		if( typeof( e.keyCode ) == 'number'  ) { //IE, NS 6+, Mozilla 0.9+
			e = e.keyCode;
		} else {
			//falha, n�o tem como obter o c�digo da tecla
			return;
		}
		if (e==13) {
			pesquisar();
		}
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
pageTitle("Usu�rio","Pesquisa");

/*
	bot�es de a��es,
	configure conforme sua necessidade
*/
$button = new Button;
$button->addItem(" Ok ","javascript:pesquisar()");
$button->addItem("Fechar","../admin/usuario_lista.php");
echo $button->writeHTML();
?>

<br>
<?
/*
	formul�rio de pesquisa
*/
$form = new Form("frm");
$form->setMethod("POST");
$form->setTarget("content");
$form->setWidth("100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s");
$form->addField("Usu�rio: ",          textField("pesq_usuario","",20,20));
$form->addField("Nome: ",             textField("pesq_nome","",50,50));
echo $form->writeHTML();
?>
</body>
</html>