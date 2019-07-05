<?
/*
	Modelo de página que apresenta um formulário
	para inclusão/alteração de registros
*/
include("../inc/common.php");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1,2");

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

// definição da expressão SQL para a função listbox
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
		função que chama a rotina de salvamento,
		altere somente o nome da página
	*/
	function salvar() {
		parent.content.document.frm.target = "controle";
		parent.content.document.frm.action = "../admin/documento_salvar.php";
		parent.content.document.frm.submit();
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
pageTitle("Documentos","Edição");

/*
	botões de ações,
	configure conforme suas necessidades
*/
$retorno = $_SERVER['QUERY_STRING'];

$button = new Button;
$button->addItem(" Salvar ","javascript:salvar()","content");
$button->addItem(" Fechar ","../admin/documento_lista.php?$retorno","content");
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
$form = new Form("frm", "../admin/documento_salvar.php", "POST", "controle", "100%");
$form->setLabelWidth("20%");
$form->setDataWidth("80%");
$form->setUpload(true);

$form->addHidden("rodou","s");        // variável de controle
$form->addHidden("f_id",              $bd_cod_documento); // chave primária
$form->addHidden("pagina",            getParam("pagina")); // número da página que chamou

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
$form->addField("Descrição: ",        textAreaField("f_descricao",stripslashes($bd_descricao),3,50,255));
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
