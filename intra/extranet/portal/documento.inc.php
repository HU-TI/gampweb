<TABLE width="100%" border='0'>
	<TR>		
		<TD valign='top'>
		<?
			$button = new Button;
			$pg = getParam("pagina");
			
			if ($pg == "") $pg = 1;
			
			$cod_categoria = getParam("cat");
			$titulo_categoria = getDbValue("SELECT categoria FROM documento_categoria WHERE cod_categoria=$cod_categoria");
			$sql = "SELECT * FROM documento WHERE cod_categoria=".getParam("cat")." ORDER BY ctr_dth_inc DESC";
			
			$rs = new query($conn, $sql, $pg, 6);

			$pg_ant = $pg-1;
			$pg_prox = $pg+1;
			
			if ($pg>1)                 $button->addItem(LISTA_ANTERIOR,$_SERVER['PHP_SELF']."?menu=documento&cat=".getParam("cat")."&pagina=$pg_ant" ,"_self");
			
			if ($pg<$rs->totalpages()) $button->addItem(utf8_encode(LISTA_PROXIMO) ,$_SERVER['PHP_SELF']."?menu=documento&cat=".getParam("cat")."&pagina=$pg_prox","_self");
			
			echo "<span class='titulo2'>".htmlentities($titulo_categoria)."</span><br>";
			echo "</td></tr><tr><td>";	
			
			for($i=0; $i<120; $i++){
				echo "<img src='../img/bkgd_principal.gif'>";
			}
			
			echo "&nbsp;</td><tr><td>".$button->writeHTML()."<br>";
			
			while ($rs->getrow()) {
				echo "<span style='float:left'><!--<a  href=index.php?menu=documentodet&cod=",$rs->field("cod_documento"),">--><span class='titulo'>",htmlentities($rs->field("titulo")),"</span>";
				echo "<SPAN class='titulo5' style='float:right'>". stod(substr($rs->field("data"),0,10))."</SPAN>";
				if($rs->field("descricao")!="") {
					echo "<br>";
					echo "<SPAN class='texto1'>".htmlentities($rs->field("descricao"))."</SPAN>";
				}
				echo "<br>";
				if(strlen($rs->field("documento"))>0) echo "<SPAN class='texto1' style='padding:0.2em'><a href='http://www.reequilibrio.com.br/extranet/arquivos/".$rs->field("documento")."' target='_blank'><img src='../img/ico_arquivo.gif' border='0' ></a></SPAN><br>";		
				for($k=0; $k<120; $k++){
					echo "<img src='../img/linha_menu.gif' width='4' height='2'>";
				}
				echo "</td><tr><tr><td>";
			}	

			if ($rs->numrows()>0) {
				echo "<div class='texto1'>".htmlentities("Página")." ".$pg." de ".$rs->totalpages()."</div><br><br>";
			} else {
				echo "<div class='subtitulo1'>Nenhum registro encontrado!</div>";
			}
		?>
		</TD>
	</TR>
</TABLE>
<!-- </div> -->
