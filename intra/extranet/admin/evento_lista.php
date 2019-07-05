<?
/*
	Modelo de página que apresenta uma lista de registros
*/
include("../inc/common.php");

/*
	verificação do nível do usuário, altere conforme sua necessidade, os números na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1,");

/*
	conexão com o banco de dados, altere somente se a conexão for diferente do default
*/
$conn = new db();
$conn->open();

/*
	determina a página a ser exibida, não precisa alterar
*/
$pg = getParam("pagina");
if ($pg == "") $pg = 1;

/*
	Limpa ordenação e filtro, não deve ser alterado
*/
if (getParam("clear")==1) {
	setSession("sOrder","");
	setSession("where","");
	setSession("pagina_atual","");
}

/*
	Salva o status da página atual, não deve ser alterado
*/
if ($_SERVER['PHP_SELF'] != $pagina_atual) {
	$mesma_pagina = false;
	setSession("pagina_atual",$_SERVER['PHP_SELF']);
} else {
	$mesma_pagina = true;
}

/*
	construção da ordenação
*/
$iSort = getParam("Sorting");
$iSorted = getParam("Sorted");
if ((!$iSort)&&(!$mesma_pagina)) {
	$form_sorting = "";
	$iSort = 2; // configure a ordenação inicial da lista de acordo com as colunas da tabela 
	$iSorted = ""; // se a ordenação estiver DESCENDENTE, repita o mesmo valor abaixo
}
if ($iSort) {
	if ($iSort == $iSorted) {
		$form_sorting = "";
		$sDirection = " DESC";
		$sSortParams = "Sorting=" . $iSort . "&Sorted=" . $iSort . "&";
	} else {
		$form_sorting = $iSort;
		$sDirection = " ASC";
		$sSortParams = "Sorting=" . $iSort . "&Sorted=" . "&";
	}
	/*
		coloque aqui a definição das ordenações das colunas de acordo com as colunas da tabela
	*/
	if ($iSort == 2) setSession("sOrder"," order by evento.txt_titulo" . $sDirection); 
	if ($iSort == 3) setSession("sOrder"," order by evento.dt_data" . $sDirection);
	if ($iSort == 4) setSession("sOrder"," order by evento.fonte" . $sDirection);
}

if (getParam("rodou")=="s") { // se ocorreu pesquisa...
	$onde = "";
	/*
		construa a string WHERE conforme o exemplo abaixo
	*/
	if (getParam("pesq_nome_usuario") != "")    $onde .= "AND evento.txt_titulo LIKE '%" . getParam("pesq_nome_usuario") . "%'";
	if (getParam("pesq_nome_real") != "")       $onde .= "AND evento.dt_data LIKE '%" .    getParam("pesq_nome_real") . "%'";
	if (getParam("pesq_departamento_id") != "") $onde .= "AND evento.fonte = " .   getParam("pesq_departamento_id") . "";
	setSession("where",$onde);
}

/*
	flag pra informar se o filtro está ou não ativo
*/
if (strlen(getSession("where"))>0) {
	$filtrado = FILTRO_ATIVO;
} else {
	$filtrado = "";
}

/*
	expressão SQL que define a lista, construa livremente observando a concatenação com as
	sessions WHERE e sOrder, conforme exemplo abaixo
*/
$sql = "SELECT * "
     . "FROM evento "
     . "WHERE 1=1 " 
	 . getSession("where") 
	 . getSession("sOrder");
	// echo $sql;
	 
/*
	criação do recordset, altere somente o último parâmetro que	corresponde a quantidade de
	registros por página
*/
$rs = new query($conn, $sql, $pg, 10);
?>
<html>
<head>
	<title>evento-lista</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="javascript" src="../inc/js/checkall.js"></script>
	
	<script language="JavaScript">
	/*
		função que chama a rotina de exclusão de registros, altere somente o nome da página
		a ser chamada
	*/
	function excluir() {
		if (confirm('Excluir registros selecionados?')) {
			parent.content.document.frm.target = "controle";
			parent.content.document.frm.action = "../admin/evento_excluir.php";
			parent.content.document.frm.submit();
		}
	}
	
	</script>
</head>
<body class="contentBODY">

<?
pageTitle("Quadro de Avisos","Lista");

/*
	botões de ações
*/
$button = new Button;

/*
	botões de navegação da lista, não deve ser alterada
*/
$pg_ant = $pg-1;
$pg_prox = $pg+1;
if ($pg>1)                 $button->addItem(LISTA_ANTERIOR,$_SERVER['PHP_SELF']."?pagina=$pg_ant" ,"content");
if ($pg<$rs->totalpages()) $button->addItem(LISTA_PROXIMO ,$_SERVER['PHP_SELF']."?pagina=$pg_prox","content");

/*
	botões de ações da lista, altere conforme suas necessidades
*/
$button->addItem("Novo","../admin/evento_edicao.php","content");
//$button->addItem("Pesquisa",   "../admin/evento_pesquisa.php","content");
$button->addItem("Excluir",    "javascript:excluir()","content");
echo $button->writeHTML();
?>
<br>
<!-- Lista -->
<div align="center">
<form name="frm" method="post">
<?
/*
	inicialização da tabela
*/
$table = new Table("","100%",6); // Título, Largura, Quantidade de colunas

/*
	Configuração das colunas da tabela
*/
$table->addColumnHeader("<input type=\"checkbox\" name=\"checkall\"onclick=\"CheckAll()\">"); // Coluna com checkbox
$table->addColumnHeader("Título",true,"50%", "L"); // Título, Ordenar?, Largura, Alinhamento
$table->addColumnHeader("Data",true,"10%","L");
$table->addColumnHeader("Publicado",true,"40%","L");
$table->addRow(); // adiciona linha (TR)

while ($rs->getrow()) {
	$id = $rs->field("cod_evento"); // captura a chave primária do recordset
	
	$table->addData("<input type=\"checkbox\" name=\"sel[]\" value=\"$id\">");
	$table->addData(addLink($rs->field("txt_titulo"),"../admin/evento_edicao.php?id=$id&pagina=$pg","Clique para consultar ou editar registro"));
	$table->addData(stod(substr($rs->field("dt_data"),0,10)));
	$table->addData($rs->field("int_publicado")==1?"Sim":"Não","L");
	$table->addRow();
}

echo "<div class='DataFONT' align='right'><b>$filtrado</b></div>";

/*
	Desenha a tabela
*/
if ($rs->numrows()>0) {
	echo $table->writeHTML();
	echo "<div class='DataFONT'>Página ".$pg." de ".$rs->totalpages()."</div>";
} else {
	echo "<div class='DataFONT'>Nenhum registro encontrado!</div>";
}
?>
</form>
</div>

</body>
</html>
<?
/*
	Fecha a conexão com o banco de dados.
*/
$conn->close();
?>