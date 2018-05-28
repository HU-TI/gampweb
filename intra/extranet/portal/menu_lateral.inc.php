<?
 if(strlen(getSession("sis_username"))!=0) {
	 $sql = "SELECT * "
	 . " FROM	documento_categoria"
	 . " WHERE 1=1 ORDER by categoria";
	 $rs = new query($conn, $sql);
	 echo "<table cellpadding=0 width=150 cellspacing=0 border=0>";
	 echo "<tr><td height='40' background='../img/bkgd_menu.gif'><a href='index.php?menu=evento' class='titulo2'>Quadro de avisos</a></td></tr>";
	 while ($rs->getrow()) {
			$id = $rs->field("cod_categoria"); // captura a chave primária do recordset	
			echo "<tr><td style='border: 0px solid blue' height='40' background='../img/bkgd_menu.gif'><a href='../portal/index.php?menu=documento&cat=".$rs->field("cod_categoria")."' class='titulo2'>".$rs->field("categoria")."</a></td></tr>";	
		}
		echo "</table>";
}
?>