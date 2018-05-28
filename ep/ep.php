<?php
//A gente pode levar o burro até a fonte, mas não pode obrigar ele a beber água
error_reporting(E_ERROR | E_WARNING | E_PARSE);	
include ('../intra/config/config.php');
include ('../intra/inc/inc.php');
?>
<!DOCTYPE html>
<html>
<link rel="shortcut icon" href="../intra/images/fav_ico.png" />
<head>
	<title>Ensino e Pesquisa</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="../intra/css/estilo.css">
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="../intra/css/estilo-ie.css" />
	<![endif]-->
</head>

<body>
	<div id="navegacao">
		<div id="area">	
			<div id="logo">
				<a href="">
					<img src="../intra/images/logo-canoas.png">
				</a>
				<a href="http://gamp-web/">
					<img src="../intra/images/logo2017.png">
				</a>
				<h1><span class="cor-padrao"></span></h1>
			</div>
			<div id="menu">
				<a href="http://gamp-web/">Home</a>
				<a href="http://gamp-web/?tela=convenio">Convênios</a>
				<a href="http://gamp-web/?tela=ramais">Ramais</a>
				<a href="http://gamp-web/?tela=arquivos">Arquivos</a>
				<a href="http://gamp-web/?tela=pots">POTS</a>

				<?php 
				    // A sessão precisa ser iniciada em cada página diferente
				    if (!isset($_SESSION)) session_start();
				    // Verifica se não há a variável da sessão que identifica o usuário
				    if (!isset($_SESSION['UsuarioID'])) {
				        // Destroi a sessão por segurança
				        session_destroy();
				        // Redireciona o visitante de volta pro login
				       // header("Location: index.php"); exit;
				    }
					if ((!isset($_SESSION['UsuarioID']))||(!isset($_SESSION['UsuarioAcesso']))) {
						header("Location: http://gamp-web/intra/inc/pagBloqueada.php"); exit;
					}else{
					 	$n_acesso = $_SESSION['UsuarioAcesso'];
					 	if($n_acesso != 2){
							header("Location: http://gamp-web/intra/inc/pagBloqueada.php"); exit;
						}
					    print '<br><div id="menu-login">
					    		Olá, ';
					    print $_SESSION['UsuarioNome'];
					    if ($n_acesso > 1) {
					    	print '<span class="dropdown"><a href="" title="Meu Menu"><img src="../intra/images/menu.png"';
					    ?>
					     	onMouseOver="this.src='../intra/images/menu-hover.png'"
							onMouseOut="this.src='../intra/images/menu.png'"
					    <?php
					    	print'></a><span class="dropdown-content">';
					    	menuAcesso($n_acesso);
  							print '</span></span>';
					    }
					     	print'<a href="http://gamp-web/?tela=config" title="Meu Perfil"><img src="../intra/images/conf.png"';
					    ?>
					    	onMouseOver="this.src='../intra/images/conf-hover.png'"
							onMouseOut="this.src='../intra/images/conf.png'"
					    <?php
					    	print '></a><a href="../intra/inc/sair.php" title="Sair"><img src="../intra/images/sair.png"';
					    ?>
					     	onMouseOver="this.src='../intra/images/sair-hover.png'"
							onMouseOut="this.src='../intra/images/sair.png'"
					    <?php
					    	print '></a></div>';
					     	//print $n_acesso;
					}
				?>
			</div>
			
		</div>

	</div>
	<!-- Conteudo -->
	<div class="center">
	</div>
	<div id="area-principal">
		<div id="conteudo"> 	
			<!-- Switch de Funções -->
		<?php
			$tela =  $_GET['tela'];
			switch ($tela) {
			  case 'epAdcCateg':
			    epAdcCategoria();
			    break;
			  case 'epViewCateg':
			    epViewCategoria($mysqli);
			    break;
			  case 'epEditaCateg':
			    epEditaCategoria();
			    break;
			  case 'epAdcTrein':
			    epAdcTreinamento();
			    break;
			  case 'epViewTrein':
			    epViewTreinamento($mysqli);
			    break;
			  case 'epEditaTrein':
			    epEditaTreinamento();
			    break;
			  case 'epAdcColab':
			    epAdcColaborador();
			    break;
			  case 'epViewColab': 
			    epViewColaboradores($mysqli);
			    break;
			  case 'epViewTodosColab': 
			    epViewTodosColab($mysqli);
			    break;
			  case 'epEditaColab':
			    epEditaColaborador();
			    break;
			  case 'epAdcTreinReal':
			    epAdcTreinRealizado();
			    break;
			  case 'epViewTreinRealizados':
			    epViewTreinRealizados($mysqli);
			    break;
			  case 'epViewTodosTreinReal':
			    epViewTodosTreinReal($mysqli);
			    break;
			  case 'epEditTreinReal':
			    epEditTreinRealizado();
			    break;
			  case 'epColabTreinReal':
			    epColabTreinRealizado($mysqli);
			    break;
			  case 'epAdcSala':
			    epAdcSala();
			    break;
			  case 'epViewSala':
			    epViewSala($mysqli);
			    break;
			  case 'epEditaSala':
			    epEditaSala();
			    break;
			  case 'epCertificado':
			    epCertificado();
			    break;
			  case 'epCertificadoCursos':
			    epCertificadoCursos();
			    break;
			  case 'epSolicitaHHT':
			    epSolicitaHHT();
			    break;
			  case 'epHHT':
			    epHHT($mysqli);
			    break;
			
			  default:
			  	epPrincipal();
			}
		?>
		</div>
		<!-- fim Conteudo -->
		<!--rodapé-->
		<div id="rodape">
			
		</div>
	</div>

</body>
<?php
	// Close Connection
	$mysqli->close();
?>
</html>