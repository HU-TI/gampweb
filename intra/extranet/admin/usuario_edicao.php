<?
/*
	Modelo de página que apresenta um formulário
	para inclusão/alteração de registros
*/
include("../inc/common.php");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");


/*
	estabelece conexão com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	tratamento de campos, caso seja edição.
	configure conforme suas necessidades
*/
$id = getParam("id"); // captura a variável que veio de objeto_lista
if (strlen($id)>0) { // edição
	$sql = "SELECT * FROM usuario WHERE cod_usuario=" . $id;
	$rs = new query($conn, $sql);
	if ($rs->getrow()) {
		$bd_cod_usuario     = $rs->field("cod_usuario");
		$bd_usuario         = $rs->field("usuario");
		$bd_nivel           = $rs->field("nivel_acesso");
		$bd_nome            = $rs->field("nome");
		$bd_email           = $rs->field("email");
		$bd_fone            = $rs->field("fone");		
	}
} else { // inclusão
	$bd_nivel = 1;
}

// definição da expressão SQL para a função listbox
$sqlNivel = "SELECT cod_nivel as id, nivel as val FROM usuario_nivel ORDER BY nivel";

?>
<html>
<head>
	<title>usuario-edicao</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="javascript" src="../inc/js/lookup.js"></script>
	<script language="JavaScript" src="../inc/js/focus.js"></script>
	<script language='JavaScript'>
	/*
		função que chama a rotina de salvamento,
		altere somente o nome da página
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../admin/usuario_salvar.php";
		parent.content.document.frm.submit();
	}
	
	/*
		exemplo de função que chama script no frame controle para complementar o
		processamento dos campos no formulário. normalmente chamado pelo onChange dos
		campos do formulário.
	*/
	function atualizaCampo(frm) {
		indice = frm.f_campo.selectedIndex;
		localizacao = "usuario_atualizar.php?pesq=" + frm.f_campo.options[indice].value;
		parent.controle.location = localizacao;
	}
	
	/*
		função que define o foco inicial do formulário,
		altere conforme o campo do formulário
	*/
	function inicializa() {
		parent.content.document.frm.f_usuario.focus();
	}
	</script>

</head>
<body class="contentBODY" onLoad="inicializa()">

<?
pageTitle("Usuário","Edição");

/*
	botões de ações,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../admin/usuario_lista.php?$retorno","content");
echo $button->writeHTML();

/*
	Controle de abas,
	true, se for a aba da página atual,
	false, se for qualquer outra aba,
	configure conforme o exemplo abaixo
*/
$abas = new Abas();
$abas->addItem("Geral",true);
echo $abas->writeHTML();

echo "<br>";

/*
	Formulário
*/
$form = new Form("frm", "../admin/usuario_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");

$form->addHidden("rodou","s"); // variável de controle
$form->addHidden("f_id",$bd_cod_usuario); // chave primária
$form->addHidden("pagina",getParam("pagina")); // número da página que chamou

$form->addField("Usuário: ",  textField("f_usuario",$bd_usuario,20,20));
$form->addField("Nome: ",     textField("f_nome",$bd_nome,50,150));
$form->addField("E-mail: ",   textField("f_email",$bd_email,50,255));
$form->addField("Fone: ",   textField("f_fone",$bd_fone,50,255));
$form->addField("Nível: ",    listboxField($sqlNivel, "f_nivel",$bd_nivel,"",""));
echo $form->writeHTML();
?>
</body>
</html>
<?
/*
	encerra a conexão com o banco de dados
*/
$conn->close();
?>
