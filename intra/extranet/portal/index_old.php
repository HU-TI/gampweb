<?
/*
Autor: Ângelo Rigo
http://www.u4w.com.br
Data: 25/10/2008
*/
/*
	Montagem dos frames da aplicação, não deve ser alterada
*/
include("../inc/common.php");
$conn = new db();
$conn->open();

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>::: <? echo "Bem-vindo ao seu reequilíbrio"; ?> :::</title>
<link href="../css/formatacao1.css" rel="stylesheet" type="text/css">
<?
echo "<!-- sis_username: ".getSession("sis_username")."-->";
if (strlen(getSession("sis_username"))==0) {
		$usuario_logado = "<span class='titulo3'>Usuário <b>ANÔNIMO</b></span>";
		$link = "&nbsp;</font><a href='index.php?menu=login_portal'  class='titulo3'>LOGIN</a>";
		$default= "login_portal.php";
} else {
	$usuario_logado = "<span class='titulo3'>Usuário: " . getSession("sis_username") . "</span>";
	$link = "&nbsp;</font><a href='index.php?menu=logout_portal' class='titulo3'>SAIR</a>";
	$default= "evento.inc.php";
}

?>
</head>
<body>

<div id="container">
 <div id="col1"></div>
   <div id="corpo">
	  <div id="menu_superior" style="align:left; border:0px solid red">
				<?
				if (strlen(getSession("sis_username"))>0) {
				?>							
					<a href="http://www.reequilibrio.com.br/extranet" target="_self">Página inicial</a> |
					<a href="http://201.34.106.155/index.asp" target="_blank">Sistema</a> |
					<a href="http://www.reequilibrio.com.br" target="_blank">Site Reequilíbrio</a> |
					<a href="index.php?menu=trocarsenha">Trocar senha</a> 				
				<?
					}
				?>
	 </div>		
		<div id="superior_I">
		  <img src="../img/img_cabec01.gif" alt="REEQUILIBIO">
		</div>
		<div id="tarja_azul">
				<div id="flash1"><B>
				<span class='titulo4' style='float:left'>Bem vindo à Extranet Reequilíbrio </span>
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
				echo "Reequilíbrio Clínica de Fisioterapia - Rua Grão Pará, 36 - Menino Deus - CEP 90850-170 - Fones (51): 
		<b>3231.0688 / 3232.4660</b>";
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
						$id = $rs->field("cod_categoria"); // captura a chave primária do recordset	
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
</body>
</html>