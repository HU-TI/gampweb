<?
/*
	Modelo de página que apresenta um formulário com critérios
	de pesquisa, os dados fornecidos nesta página serão
	processados na página de lista.
*/
include("../inc/common.php");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
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
		função que chama a página de lista ou relatório,
		enviando os parâmetros de pesquisa,
		altere somente o nome da página
	*/
	function pesquisar() {
		parent.content.document.frm.target = "content";
		parent.content.document.frm.action = "../admin/usuario_lista.php";
		parent.content.document.frm.submit();
	}
	
	/*
		função que define o foco inicial do formulário
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
		if( !e ) { //verifica se é IE
			if( window.event ) {
				e = window.event;
			} else {
				//falha, não tem como capturar o evento
				return;
			}
		}
		if( typeof( e.keyCode ) == 'number'  ) { //IE, NS 6+, Mozilla 0.9+
			e = e.keyCode;
		} else {
			//falha, não tem como obter o código da tecla
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
pageTitle("Usuário","Pesquisa");

/*
	botões de ações,
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
	formulário de pesquisa
*/
$form = new Form("frm");
$form->setMethod("POST");
$form->setTarget("content");
$form->setWidth("100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s");
$form->addField("Usuário: ",          textField("pesq_usuario","",20,20));
$form->addField("Nome: ",             textField("pesq_nome","",50,50));
echo $form->writeHTML();
?>
</body>
</html>