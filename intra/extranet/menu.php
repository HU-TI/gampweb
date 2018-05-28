
<?php
include "config.php";
		$sql = mysql_query("SELECT * FROM documento_categoria ORDER by categoria");
				if (!$sql){
				print "Não foi possivel a Conexao";
				}
				else{
				while ($sel = mysql_fetch_array($sql)){
						$idMenu = $sel['cod_categoria'];
						$categoria = $sel['categoria'];
						$linha_apoio =  wordwrap($categoria, 12, "<br />\n");   ($categoria);
						 if (strlen($linha_apoio) <= 12){
							print'<li class="txt-menu-lateral">
							<a href="?menu=documento&cat='.$idMenu.'&src=icone-arquivos.jpg&titulo='.$categoria.'">'.$linha_apoio.'</a></li>
								 <li><img src="images/ornamento.png"></li>'; 
						 }else{
						 print'<li class="txt-menu-lateral2">
							<a href="?menu=documento&cat='.$idMenu.'&src=icone-arquivos.jpg&titulo='.$categoria.'">'.$linha_apoio.'</a></li>
						 <li><img src="images/ornamento.png"></li>';
						 }
					}
				}
?>
