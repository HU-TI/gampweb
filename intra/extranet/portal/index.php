<?
/*
	Montagem dos frames da aplica��o, n�o deve ser alterada
*/
include("../inc/common.php");
$conn = new db();
$conn->open();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>::: <? echo utf8_encode("Bem-vindo ao seu reequil�brio"); ?> :::</title>
<link href="../css/formatacao1.css" rel="stylesheet" type="text/css">
<?
echo "<!-- sis_username: ".getSession("sis_username")."-->";
if (strlen(getSession("sis_username"))==0) {
		$usuario_logado = "<span class='titulo3'>".utf8_encode("Usu�rio")." <b>".utf8_encode("AN�NIMO")."</b></span>";
		$link = "&nbsp;</font><a href='index.php?menu=login_portal'  class='titulo3'>LOGIN</a>";
		$default= "login_portal.php";
} else {
	$usuario_logado = "<span class='titulo3'>".utf8_encode("Usu�rio:")." " . getSession("sis_username") . "</span>";
	$link = "&nbsp;</font><a href='index.php?menu=logout_portal' class='titulo3'>SAIR</a>";
	$default= "evento.inc.php";
}

?>
</head>
	<BODY class=BODY topmargin="4" leftmargin="0" marginwidth="0" marginheight="4">
		<div id="container">
 <div id="col1"></div>
   <div id="corpo">
	  <div id="menu_superior" style="align:left; border:0px solid red">
				<?
				if (strlen(getSession("sis_username"))>0) {
				?>							
					<a href="http://www.reequilibrio.com.br/extranet" target="_self"><?php echo utf8_encode("P�gina inicial"); ?></a> |
					<a href="http://201.86.212.141/index.asp" target="_blank">Sistema</a> |
					<a href="http://www.reequilibrio.com.br" target="_blank"><?php echo utf8_encode("Site Reequil�brio"); ?></a> |
					<a href="index.php?menu=trocarsenha">Trocar senha</a> 				
				<?
					}
				?>
	 </div>		
		<div id="superior_I">
		  <img src="../img/img_cabec01.gif" alt="REEQUILIBIO">
		</div>
		<div id="tarja_azul">
				<div id="flash1">
					<B>
						<span class='titulo4' style='float:left'>
							<?php echo utf8_encode("Bem vindo � Extranet Reequil�brio"); ?>
						</span>
				</B>
				</div>
				</div>
				<div id="flash2">
				<span style='float:right' style="border:0px solid red"><?=$usuario_logado?>&nbsp;&nbsp;<?=$link?></span>
				</div>			
		<div id="logo"><a href="http://www.reequilibrio.com.br/">
		<img src="../img/logo2.gif" alt="reequilibrio" border="0"></a>
		</div>
		<div id="endereco"></div>
<div id="corpo_branco" style="border-left:2px solid #007CC3">
	<div id="clinica"> 
				<?
			$menu = getParam("menu");
			switch ($menu) {
				case "evento":
					include "evento.inc.php";
				break;				
				case "documento":
					include "documento.inc.php";
				break;				
				case "login_portal":
					include "login_portal.php";
				break;	
				case "logout_portal":
					include "logout_portal.php";
				break;
				case "login_validar_portal":
					include "login_validar_portal.php";
				break;	
				case "trocarsenha":
					include "usuario_troca_senha_edicao.php";
				break;	
				case "trocarsenhasalvar":
					include "usuario_troca_senha_salvar.php";
				break;	
				case "resetar":
					include "usuario_senha_resetar.php";
				break;	
				default:
					include $default;
			}
			?>
				</div>		
		<div id="parceiros"></div>
		<div id="rodape">
			<?php 
				echo utf8_encode("Reequil�brio Cl�nica de Fisioterapia - Rua Gr�o Par�, 36 - Menino Deus - CEP 90850-170 - Fones (51): <b>3231.0688 / 3232.4660</b>");
		?>
	  </div>
		<div id="menu_index">
		<?
			 if(strlen(getSession("sis_username"))!=0) {
				 $sql = "SELECT * "
				 . " FROM	documento_categoria"
				 . " WHERE 1=1 ORDER by categoria";
				 $rs = new query($conn, $sql);
				 echo "<ul class='menu_lista'>";
				 echo "<li><a href='index.php?menu=evento' class='titulo3'>Quadro de avisos</a><br>";
				 for($i=0; $i<38; $i++){
							echo "<img src='../img/linha_menu.gif'>";
						}
				 echo "</li>";
				 while ($rs->getrow()) {
						$id = $rs->field("cod_categoria"); // captura a chave prim�ria do recordset	
						echo "<li><a href='../portal/index.php?menu=documento&cat=".$rs->field("cod_categoria")."' class='titulo3'>".htmlentities($rs->field("categoria"))."</a><br>";
						for($i=0; $i<38; $i++){
							echo "<img src='../img/linha_menu.gif' style='padding-bottom:4px'>";
						}
						echo "</li>";
				}
				echo "</ul>";
			}
		?>			
		</div>
  	</div>
 </div>
 <div id="col2"></div>
</div>
	</BODY>
</HTML>