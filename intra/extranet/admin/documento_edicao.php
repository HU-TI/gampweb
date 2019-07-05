<?
/*
	Modelo de p�gina que apresenta um formul�rio
	para inclus�o/altera��o de registros
*/
include("../inc/common.php");

/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1,2");

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
	$sql = "SELECT * FROM documento WHERE cod_documento=" . $id;
	$rs = new query($conn, $sql);
	if ($rs->getrow()) { 
		$bd_cod_documento       = $rs->field("cod_documento");
		$bd_titulo              = $rs->field("titulo");
		$bd_arquivo             = $rs->field("documento");
		$bd_descricao           = $rs->field("descricao");
		$bd_cod_categoria       = $rs->field("cod_categoria");
		$bd_data			= stod(substr($rs->field("data"),0,10));
		
	}
} else{
	$bd_data = date("d/m/Y");
}

// defini��o da express�o SQL para a fun��o listbox
$sqlCategoria = "SELECT cod_categoria as id, categoria as val FROM documento_categoria ORDER BY categoria";

?>
<html>
<head>
	<title>documento-edicao</title>
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
		parent.content.document.frm.action = "../admin/documento_salvar.php";
		parent.content.document.frm.submit();
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
pageTitle("Documentos","Edi��o");

/*
	bot�es de a��es,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../admin/documento_lista.php?$retorno","content");
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
$form = new Form("frm", "../admin/documento_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");
$form->setUpload(true);

$form->addHidden("rodou","s");        // vari�vel de controle
$form->addHidden("f_id",              $bd_cod_documento); // chave prim�ria
$form->addHidden("pagina",            getParam("pagina")); // n�mero da p�gina que chamou

$form->addField("titulo: ",           textField("f_titulo",$bd_titulo,60,255));
$form->addField("Data da evento: ",  dateField("f_data",$bd_data,"readonly"));
$form->addField("Categoria: ",        listboxField($sqlCategoria, "f_cod_categoria",$bd_cod_categoria,"",""));
if($bd_arquivo == "") {
   $form->addField("Arquivo: ",        fileField("f_arquivo","",60,255));
}
else {
   $form->addField("Arquivo atual: ", $bd_arquivo);
   $form->addField("Novo Arquivo: ",   fileField("f_arquivo","",60,255));
}
$form->addField("Descri��o: ",        textAreaField("f_descricao",stripslashes($bd_descricao),3,50,255));
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
