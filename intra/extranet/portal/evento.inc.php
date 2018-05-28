<TABLE width="100%" border='0'>
	<TR>		
		<TD valign='top'>
<?
	$button = new Button;
	$pg = getParam("pagina");
	if ($pg == "") $pg = 1;	
	
	$sql = "SELECT * FROM evento ORDER BY dt_data DESC";	
	$rs = new query($conn, $sql, $pg, 4);

	$pg_ant = $pg-1;
	$pg_prox = $pg+1;
	
	if ($pg>1)                 $button->addItem(LISTA_ANTERIOR,$_SERVER['PHP_SELF']."?pagina=$pg_ant" ,"_self");
	
	if ($pg<$rs->totalpages()) $button->addItem(LISTA_PROXIMO ,$_SERVER['PHP_SELF']."?pagina=$pg_prox","_self");	
	echo "<span class='titulo2'>Quadro de Avisos</span><br>";
	echo "</td></tr><tr><td>";
		
	for($i=0; $i<120; $i++){
		echo "<img src='../img/bkgd_principal.gif'>";
	}
	
	echo "&nbsp;</td><tr><td align=right>";
	echo "<div style='font-family:verdana; size:10px'>";
	echo utf8_encode($button->writeHTML());
	echo "</div>";
	echo "<br>";
	
	while ($rs->getrow()) {
		
		echo "<span style='float:left; border: 0px solid red' class='titulo'><!-- <a class='titulo' href=index.php?menu=eventodet&cod=",$rs->field("cod_evento"),"> -->",htmlentities($rs->field("txt_titulo")),"<!-- </a> --></span>";
		echo "<SPAN class='titulo5' style='float:right'>". stod(substr($rs->field("dt_data"),0,10))."</SPAN>";
		echo "<br>";
		echo "<SPAN class='texto1' style='float:left; border: 0px solid red'>".htmlentities($rs->field("txt_texto"))."</SPAN>";
		echo "<br><br>";
		if(strlen($rs->field("documento"))>0) echo "<SPAN class='texto1'><a href='http://www.u4w.com.br/clientes/reequilibrio/arquivos/".$rs->field("documento")."'><img src='../img/ico_arquivo.gif' border='0'></a></SPAN><br><br>";	
		for($k=0; $k<120; $k++){
		    echo "<img src='../img/linha_menu.gif' width='4' height='2'>";
	    }
		echo "</td><tr><tr><td>";
		
	}
	if ($rs->numrows()>0) {
		echo "<div class='texto1'>".htmlentities("Página")." ".$pg." de ".$rs->totalpages()."</div>";
	} else {
		echo "<div class='subtitulo1'>Nenhum registro encontrado!</div>";
	}
?>
</TD>
	</TR>
</TABLE>
<!-- </div> -->
