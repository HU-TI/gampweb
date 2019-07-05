<?
/*
	Página que apresenta lista de seleção,
	NÃO DEVE SER ALTERADA
*/
include_once("../inc/common.php");

// captura argumentos passados
if (getParam("rodou_pesq") != "sim") {
	setSession("campo_form", getParam("nomeCampoForm"));
	setSession("tabela", getParam("nomeTabela"));
	setSession("campo_chave", getParam("nomeCampoChave"));
	setSession("campo_exibicao", getParam("nomeCampoExibicao"));
	setSession("campo_auxiliar", getParam("nomeCampoAuxiliar"));
	setSession("titulo_lista", getParam("titulo"));
	$strWhere = "";
} else {
	$strWhere  = " WHERE ".getSession("campo_exibicao")." LIKE '%".getParam("f_txtPesquisa")."%' ";
}

// conexão com o banco de dados
$conn = new db();
$conn->open();

// expressão SQL que monta a lista
if (strlen(trim(getSession("campo_auxiliar")))>0) {
	$c = ", ".getSession("campo_auxiliar")." AS auxiliar ";
} else {
	$c = "";
}
$sql = "SELECT " . getSession("campo_chave") . " AS chave, " . getSession("campo_exibicao") . " AS label " .$c.
       "FROM " . getSession("tabela") . " " .
       $strWhere .
       "ORDER BY " . getSession("campo_exibicao");
$rs = new query($conn, $sql, 1, LOOKUP_MAX_REC);
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
	<title>Selecione o registro</title>
	<script language="javascript" type="text/javascript">
	function update(valor, descricao) {
		opener.parent.content.document.forms[0].<?=getSession("campo_form")."Dummy"?>.value = descricao;
		opener.parent.content.document.forms[0].<?=getSession("campo_form")?>.value = valor;
		window.self.close();
	}
	</script>
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
</head>

<body class="contentBODY" onLoad="javascript:document.forms[0].f_txtPesquisa.focus();">

<!-- identificação da página -->
<?
pageTitle(getSession("titulo_lista"),LOOKUP_SUBTITULO);
?>
<div class="acoes">
	<form name="frm" action="<?=$_SERVER['PHP_SELF']?>" method="get">
		<font class="LabelFont"><?=LOOKUP_TITULO_PESQUISA?></font>
		<input type="text" size="15" name="f_txtPesquisa">
		<input type="submit" name="Busca" value=" Ok ">
		<input type="hidden" name="rodou_pesq" value="sim">
	</form>
</div>
<div class="acoes">
	<a class="link" href="javascript:update(0,'')"><?=LOOKUP_RESET?></a>
</div>
<?
$col = (strlen(trim(getSession("campo_auxiliar")))>0)?2:1;
$table = new Table("","100%",$col);
while ($rs->getrow()) {
	$table->addData("<a class=\"link\" href=\"javascript:update('".$rs->field("chave")."','".$rs->field("label")."')\">".$rs->field("label")."</a>");
	if ($col==2) {
		$table->addData($rs->field("auxiliar"));
	}
	$table->addRow();
}
echo $table->writeHTML();
// encerra a conexão com o banco de dados
$conn->close();
?>
</body>
</html>