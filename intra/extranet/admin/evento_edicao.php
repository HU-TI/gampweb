<?
/*
	Modelo de p�gina que apresenta um formul�rio
	para inclus�o/altera��o de registros
*/
include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");


/*
	estabelece conex�o com o banco de dados
*/
$conn = new db();
$conn->open();

/*
	tratamento de campos, caso seja edi��o.
	configure conforme suas necessidades
*/
$id = getParam("id"); // captura a vari�vel que veio de objeto_lista
if (strlen($id)>0) { // edi��o
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
} else { // inclus�o
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
		fun��o que chama a rotina de salvamento,
		altere somente o nome da p�gina
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../admin/evento_salvar.php";
		parent.content.document.frm.submit();
	}
	
	/*
		exemplo de fun��o que chama script no frame controle para complementar o
		processamento dos campos no formul�rio. normalmente chamado pelo onChange dos
		campos do formul�rio.
	*/
	function atualizaCampo(frm) {
		indice = frm.f_campo.selectedIndex;
		localizacao = "evento_atualizar.php?pesq=" + frm.f_campo.options[indice].value;
		parent.controle.location = localizacao;
	}
	
	/*
		fun��o que define o foco inicial do formul�rio,
		altere conforme o campo do formul�rio
	*/
	function inicializa() {
		parent.content.document.frm.f_titulo.focus();
	}
	</script>

</head>
<body class="contentBODY" onload="inicializa()">

<?
pageTitle("Quadro de avisos","Edi��o");

/*
	bot�es de a��es,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../admin/evento_lista.php?$retorno","content");
echo $button->writeHTML();

/*
	Controle de abas,
	true, se for a aba da p�gina atual,
	false, se for qualquer outra aba,
	configure conforme o exemplo abaixo
*/
$abas = new Abas();
$abas->addItem("Geral",true);
echo $abas->writeHTML();

echo "<br>";

/*
	Formul�rio
*/
$form = new Form("frm", "../admin/evento_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");
$form->setUpload(true);

$form->addHidden("rodou","s"); // vari�vel de controle
$form->addHidden("f_id",              $bd_cod_evento); // chave prim�ria
$form->addHidden("pagina",            getParam("pagina")); // n�mero da p�gina que chamou
if($bd_imagem != "") {
   $form->addHidden("f_imagemOld",    $bd_imagem); 
}

$form->addField("Titulo: ",           textField("f_txt_titulo",$bd_txt_titulo,60,255));
$form->addField("Data da evento: ",  dateField("f_dt_data",$bd_dt_data,"readonly"));
$form->addField("Hor�rio: ",		textField("f_dt_hora",$bd_hora,20,30));
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
	encerra a conex�o com o banco de dados
*/
$conn->close();
?>
