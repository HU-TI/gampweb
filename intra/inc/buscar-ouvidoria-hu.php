<?php
session_start();//INICIAR SESSÃO PARA SERIALIZAÇÃO
if( isset($_POST['pesquisa']) ){
	$pesquisa = $_POST['pesquisa'];
}

$mysqli = new mysqli('localhost', 'root', '', 'intra_gamp');

if ( $mysqli->connect_errno ) {
  echo $mysqli->connect_errno, ' ', $mysqli->connect_error;
}

mysqli_set_charset($mysqli,"utf8");

$sql = "SELECT * FROM ouvidoria_hu WHERE numeroOuvidoriaRegistro = '$pesquisa'";

$resultado = $mysqli->query($sql);

if($resultado->num_rows > 0){
	$ouvidoriaEncontrada = $resultado->fetch_assoc();
}else{
	$ouvidoriaEncontrada = null;
}

$_SESSION['ouvidoria'] = serialize($ouvidoriaEncontrada);

header('location:../../index.php?tela=alterarOuvidoriaEncontrada');	

?>