<?
/*
	Modelo de p�gina que apresenta uma lista de registros
*/
include("../inc/common.php");


/*
	verifica��o do n�vel do usu�rio, altere conforme sua necessidade, os n�meros na string representam os grupos permitidos
*/
verificaPermissaoPagina("10,1");



/*
	conex�o com o banco de dados, altere somente se a conex�o for diferente do default
*/
$conn = new db();
$conn->open();


/*
	determina a p�gina a ser exibida, n�o precisa alterar
*/
$pg = getParam("pagina");
if ($pg == "") $pg = 1;


/*
	Limpa ordena��o e filtro, n�o deve ser alterado
*/
if (getParam("clear")==1) {
	setSession("sOrder","");
	setSession("where","");
	setSession("pagina_atual","");
}

/*
	Salva o status da p�gina atual, n�o deve ser alterado
*/
if ($_SERVER['PHP_SELF'] != $pagina_atual) {
	$mesma_pagina = false;
	setSession("pagina_atual",$_SERVER['PHP_SELF']);
} else {
	$mesma_pagina = true;
}


/*
	constru��o da ordena��o
*/
$iSort = getParam("Sorting");
$iSorted = getParam("Sorted");
if ((!$iSort)&&(!$mesma_pagina)) {
	$form_sorting = "";
	$iSort = 2; // configure a ordena��o inicial da lista de acordo com as colunas da tabela 
	$iSorted = ""; // se a ordena��o estiver DESCENDENTE, repita o mesmo valor abaixo
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
		coloque aqui a defini��o das ordena��es das colunas de acordo com as colunas da tabela
	*/
	if ($iSort == 2) setSession("sOrder"," order by usuario.usuario" . $sDirection); 
	if ($iSort == 3) setSession("sOrder"," order by usuario.nome" . $sDirection);
	if ($iSort == 4) setSession("sOrder"," order by usuario.nivel_acesso" . $sDirection);
}



if (getParam("rodou")=="s") { // se ocorreu pesquisa...
	$onde = "";
	/*
		construa a string WHERE conforme o exemplo abaixo
	*/
	if (getParam("pesq_usuario") != "") $onde .= "AND usuario.usuario LIKE '%" . getParam("pesq_usuario") . "%'";
	if (getParam("pesq_nome") != "") $onde .= "AND usuario.nome LIKE '%" . getParam("pesq_nome") . "%'";
	setSession("where",$onde);
}

/*
	flag pra informar se o filtro est� ou n�o ativo
*/
if (strlen(getSession("where"))>0) {
	$filtrado = FILTRO_ATIVO;
} else {
	$filtrado = "";
}


/*
	express�o SQL que define a lista, construa livremente observando a concatena��o com as
	sessions WHERE e sOrder, conforme exemplo abaixo
*/
$sql = "SELECT usuario.* "
     . "FROM usuario "
     . "WHERE 1=1 " . getSession("where") . getSession("sOrder");
	  

/*
	cria��o do recordset, altere somente o �ltimo par�metro que	corresponde a quantidade de
	registros por p�gina
*/
$rs = new query($conn, $sql, $pg, 10);

$listaNivel = getDbArray("select cod_nivel, nivel from usuario_nivel");
?>
<html>
<head>
	<title>usuario-lista</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<link rel="stylesheet" type="text/css" href="<?=CSS_CONTENT?>">
	<script language="javascript" src="../inc/js/checkall.js"></script>
	
	<script language="JavaScript">
	/*
		fun��o que chama a rotina de exclus�o de registros, altere somente o nome da p�gina
		a ser chamada
	*/
	function excluir() {
		if (confirm('Excluir registros selecionados?')) {
			parent.content.document.frm.target = "controle";
			parent.content.document.frm.action = "../admin/usuario_excluir.php";
			parent.content.document.frm.submit();
		}
	}
	
	function resetarSenha() {
		if (confirm('Confirma a reinicializa��o da senha do usu�rio selecionado?')) {
			parent.content.document.frm.target = "controle";
			parent.content.document.frm.action = "../admin/usuario_senha_resetar.php";
			parent.content.document.frm.submit();
		}
	}
	</script>
</head>
<body class="contentBODY">

<?
pageTitle("Usu�rio","Lista");

/*
	bot�es de a��es
*/
$button = new Button;

/*
	bot�es de navega��o da lista, n�o deve ser alterada
*/
$pg_ant = $pg-1;
$pg_prox = $pg+1;
if ($pg>1)                 $button->addItem(LISTA_ANTERIOR,$_SERVER['PHP_SELF']."?pagina=$pg_ant" ,"content");
if ($pg<$rs->totalpages()) $button->addItem(LISTA_PROXIMO ,$_SERVER['PHP_SELF']."?pagina=$pg_prox","content");

/*
	bot�es de a��es da lista, altere conforme suas necessidades
*/
$button->addItem("Novo usu�rio","../admin/usuario_edicao.php","content");
$button->addItem("Pesquisa","../admin/usuario_pesquisa.php","content");
$button->addItem("Reiniciar senha","javascript:resetarSenha()","content");
$button->addItem("Excluir","javascript:excluir()","content");
echo $button->writeHTML();
?>

<!-- Lista -->
<div align="center">
<form name="frm" method="post">
<?
/*
	inicializa��o da tabela
*/
$table = new Table("","100%",4); // T�tulo, Largura, Quantidade de colunas

/*
	Configura��o das colunas da tabela
*/
$table->addColumnHeader("<input type=\"checkbox\" name=\"checkall\"onclick=\"CheckAll()\">"); // Coluna com checkbox
$table->addColumnHeader("Usu�rio",true,"25%", "L"); // T�tulo, Ordenar?, Largura, Alinhamento
$table->addColumnHeader("Nome",true,"25%","L");
$table->addColumnHeader("Email",true,"25%","L");
$table->addColumnHeader("Fone",true,"25%","L");
$table->addColumnHeader("Nivel",true,"25%","C");
$table->addRow(); // adiciona linha (TR)

while ($rs->getrow()) {
	$id = $rs->field("cod_usuario"); // captura a chave prim�ria do recordset
	
	$table->addData("<input type=\"checkbox\" name=\"sel[]\" value=\"$id\">");
	$table->addData(addLink($rs->field("usuario"),"../admin/usuario_edicao.php?id=$id&pagina=$pg","Clique para consultar ou editar registro"));
	$table->addData($rs->field("nome"));
	$table->addData($rs->field("email"));
	$table->addData($rs->field("fone"));	
	//$pos = $rs->field("nivel_acesso");
	$table->addData($listaNivel[$rs->field("nivel_acesso")],"C");
	$table->addRow();
}

echo "<div class='DataFONT' align='right'><b>$filtrado</b></div>";

/*
	Desenha a tabela
*/
if ($rs->numrows()>0) {
	echo $table->writeHTML();
	echo "<div class='DataFONT'>P�gina ".$pg." de ".$rs->totalpages()."</div>";
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
	fecha a conex�o com o banco de dados, n�o deve ser alterado
*/
$conn->close();
?>