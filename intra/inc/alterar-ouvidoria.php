<?php

	if( isset($_POST['numeroOuvidoriaRegistro']) ){
		$numeroOuvidoriaRegistro = $_POST['numeroOuvidoriaRegistro'];
	}
	if( isset($_POST['resposta']) ){
		$resposta = $_POST['resposta'];
	}
	if( isset($_POST['teveResposta']) ){
		$teveResposta = $_POST['teveResposta'];
	}
	
	$mysqli = new mysqli('localhost', 'root', '', 'intra_gamp');

	if ( $mysqli->connect_errno ) {
	  echo $mysqli->connect_errno, ' ', $mysqli->connect_error;
	}
	
	mysqli_set_charset($mysqli,"utf8");
	
	if($_POST['prazoConclusao'] == 'hu'){
		$alterarRegistroOuvidoria = "
			UPDATE ouvidoria_hu SET resposta = '$resposta', teveResposta = '$teveResposta' 
			WHERE numeroOuvidoriaRegistro = '$numeroOuvidoriaRegistro'";	
	}else if($_POST['prazoConclusao'] == 'hpsc'){
		$alterarRegistroOuvidoria = "
			UPDATE ouvidoria_hpsc SET resposta = '$resposta', teveResposta = '$teveResposta' 
			WHERE numeroOuvidoriaRegistro = '$numeroOuvidoriaRegistro'";	
	}
	
	$mysqli->query($alterarRegistroOuvidoria);
	
	print '<script>location.href=\'../../index.php?tela=sucessoBaixaOuvidoria\';</script>';
	
?>