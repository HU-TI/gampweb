<?php
 $usuario= $_COOKIE['username']; 
require 'config.php';
        $sql = mysql_query("SELECT nome, usuario FROM usuario where usuario='$usuario' LIMIT 1");
        while ($sel = mysql_fetch_array($sql)){
        $usuario = $sel['usuario'];
        $nome = $sel['nome'];
    }

include "valida_cookies.php";
error_reporting(E_ERROR | E_WARNING | E_PARSE);  
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
<head>
<title>Rede Extranet MAED</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script language="JavaScript">

function abrir(URL) {

  var width = 300;
  var height = 200;

  var left = 99;
  var top = 99;

  window.open(URL,'a', 'width='+width+', height='+height+', top='+top+', left='+left+', scrollbars=yes, status=no, toolbar=no, location=no, directories=no, menubar=no, resizable=no, fullscreen=no');
}
</script>
</head>
<link href="css/extranet.css" rel="stylesheet" type="text/css" />
<body>
<?php
  $menu     = $_GET['menu'];
  $texto     = $_GET['texto'];
  $titulo   = $_GET['titulo'];
  $src       =  $_GET['src'];
  $mais_dicas = $_GET['mais_dicas'];
?>
<div id="site">
   <div id="menu-lateral">
   <ul>
         <li><img src="images/menu-lateral.png"></li>
         <li class="txt-menu-lateral"><a href="?menu=evento&src=icone-QA.jpg&titulo=Quadro de Avisos"> Quadro de Avisos</a></li>
         <li><img src="images/ornamento.png"></li>
         <li class="txt-menu-lateral"><a href="?menu=contato&src=icone-profissionais.jpg&titulo=Profissionais"> Profissionais</a></li>
         <li><img src="images/ornamento.png"></li>
   <?php
   include "menu.php";
   ?>  
  </ul>     
   </div>
  <div id="corpo">
      <div id="banner">
      </div>
      <div id="menu-secundario">
         <ul>
            <li class="menu-1"><a href="http://maedfisioterapia.com.br">SITE MAED</a></li>
            <li class="menu-2"><a href="http://reequilibrio.wsigapp.com/hospital/">SISTEMA HOSPITAL</a></li>
            <li class="menu-2"><a href="javascript:abrir('alterarsenha.php?usuario=<?php print $usuario; ?>');" class="left_link">ALTERAÇÃO SENHA</a></li>
            <li class="menu-1"><a href="logout.php">SAIR</a></li>
         </ul>
      </div>
<?php
include "miolo.php";
?>
   </div>
</body>
</html>
