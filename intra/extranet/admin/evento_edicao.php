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
	$sql = "SELECT * FROM evento WHERE cod_evento=" . $id;
	$rs = new query($conn, $sql);
	if ($rs->getrow()) {
		$bd_cod_evento		= $rs->field("cod_evento");
		$bd_txt_titulo      = $rs->field("txt_titulo");
		$bd_txt_texto		= $rs->field("txt_texto");
		$bd_dt_data			= stod(substr($rs->field("dt_data"),0,10));
		$bd_hora		= $rs->field("hora");
		//$bd_local		= $rs->field("local");
		$bd_int_publicado	= $rs->field("int_publicado");
	}
} else { // inclusão
	$bd_dt_data = date("d/m/Y");
}
?>
<html>
<head>
	<title>evento-edicao</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
 <?=carregaJS("popcalendar,lookup,focus,textcounter,editor");?>
	<script language='JavaScript'>
	/*
		função que chama a rotina de salvamento,
		altere somente o nome da página
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../admin/evento_salvar.php";
		parent.content.document.frm.submit();
	}
	
	/*
		exemplo de função que chama script no frame controle para complementar o
		processamento dos campos no formulário. normalmente chamado pelo onChange dos
		campos do formulário.
	*/
	function atualizaCampo(frm) {
		indice = frm.f_campo.selectedIndex;
		localizacao = "evento_atualizar.php?pesq=" + frm.f_campo.options[indice].value;
		parent.controle.location = localizacao;
	}
	
	/*
		função que define o foco inicial do formulário,
		altere conforme o campo do formulário
	*/
	function inicializa() {
		parent.content.document.frm.f_titulo.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
pageTitle("Quadro de avisos","Edição");

/*
	botões de ações,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../admin/evento_lista.php?$retorno","content");
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
$form = new Form("frm", "../admin/evento_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");
$form->setUpload(true);

$form->addHidden("rodou","s"); // variável de controle
$form->addHidden("f_id",              $bd_cod_evento); // chave primária
$form->addHidden("pagina",            getParam("pagina")); // número da página que chamou
if($bd_imagem != "") {
   $form->addHidden("f_imagemOld",    $bd_imagem); 
}

$form->addField("Titulo: ",           textField("f_txt_titulo",$bd_txt_titulo,60,255));
$form->addField("Data da evento: ",  dateField("f_dt_data",$bd_dt_data,"readonly"));
$form->addField("Horário: ",		textField("f_dt_hora",$bd_hora,20,30));
//$form->addField("Local: ",           textField("f_txt_local",$bd_local,60,255));
$form->addField("Texto: ",            textAreaFieldEditor("f_txt_texto",$bd_txt_texto,15,80));
//if($bd_imagem == "") {
//   $form->addField("Imagem: ",        fileField("f_imagem","",60,255));
//}
//else {
//   $form->addField("Imagem atual: ",  $bd_imagem);
//   $form->addField("Nova Imagem: ",   fileField("f_imagem","",60,255));
//}
$form->addField("Publicado: ",        checkboxField("f_int_publicado",1,$bd_int_publicado==1));

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
