<?php 

	$nome = $_REQUEST['nome'];
   	$cursos = '';
  	$_checkbox = $_POST['curso'];
	$_data = $_POST['data'];
	$_tempo = $_POST['tempo'];
	foreach($_checkbox as $_valor => $curso){
		if (!$cursos){
			$cursos = '<tr><td>'.$curso.'</td><td>'.$_tempo[$_valor].'</td><td>'.$_data[$_valor];
		}else{
			$cursos = $cursos.'</td></tr><tr><td>'.$curso.'</td><td>'.$_tempo[$_valor].'</td><td>'.$_data[$_valor];	
		}
	}
	$cursos = $cursos.'</td></tr>';
	
	//Iniciar o buffer
	ob_start();

	//pegar o conteurdo do buffer, inserir na variavel e limpar a memoria
	$html = ob_get_clean();

	//converte o conteudo para utf-8
	$html = utf8_encode($html);
	//Conteúdo do PDF
	$html = '
		<html>
		<head>
			<title>Certificado</title>
			<meta charset="utf-8">
			<link rel="stylesheet" type="text/css" href="css/estilo.css"><style type="text/css"></style>
		</head>
		<body style="background-image: url(img/FRENTE2-2.png);background-repeat: no-repeat;background-position: center center;background-attachment: fixed;background-size: 100%;">
			<h2>'.$nome.'</h2>
		</body>
		</html>
	';

	$html2 = ob_get_clean();

	//converte o conteudo para utf-8
	$html2 = utf8_encode($html);
	//Conteúdo do PDF
	$html2 = '
		<html>
		<head>
			<title>Certificado</title>
			<meta charset="utf-8">
		</head>
		<body style="background-image: url(img/VERSO2.png);background-repeat: no-repeat;background-position: center center;background-attachment: fixed;background-size: 100%;">
				<div class="treinamentos">
					<table>
						<tr style="background: #F2F2F2;">
							<td><h3 class="center">Cursos</h3></td>
							<td><h3 class="center">Carga Horária</h3></td>
							<td><h3 class="center">Data</h3></td>
						</tr>
						'.$cursos.'
					</table>
				</div>
		</body>
		</html>
	';

	//incluir a classe MPDF
	include('mpdf/mpdf.php');

	$mpdf = new mPDF('utf-8', 'A4-L');

	$mpdf->allow_charset_conversion = true;

	$mpdf->charset_in = 'UTF-8';

	$mpdf->WriteHTML($html);
	$mpdf->AddPage();//Nova página
	$mpdf->WriteHTML($html2);
	//Primeiro campo é o nome do arquivo. Segundo campo I-> Vizualiza PDF D-> Download do PDF
	$mpdf->Output('certificado','I');

	exit();
?>	