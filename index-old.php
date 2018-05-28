<?php
//A gente pode levar o burro até a fonte, mas não pode obrigar ele a beber água
error_reporting(E_ERROR | E_WARNING | E_PARSE);	
include ('intra/config/config.php');
include ('intra/inc/inc.php');
?>
<!DOCTYPE html>
<html>
<link rel="shortcut icon" href="intra/images/fav_ico.png" />
<head>
	<title>Intranet GAMP</title>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="intra/css/estilo.css">
	<!--[if IE]>
		<link rel="stylesheet" type="text/css" href="intra/css/estilo-ie.css" />
	<![endif]-->
</head>

<body>
	<div id="navegacao">
		<div id="area">	
			<div id="logo">
				<a href="">
					<img src="intra/images/logo-canoas.png">
				</a>
				<a href="http://gamp-web/">
					<img src="intra/images/logo2017.png">
				</a>
				<h1><span class="cor-padrao"></span></h1>
			</div>
			<div id="menu">
				<a href="?">Home</a>
				<a href="?tela=convenio">Convênios</a>
				<a href="?tela=ramais">Ramais</a>
				<a href="?tela=arquivos">Arquivos</a>
				<a href="?tela=pots">POTS</a>

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
					if (!isset($_SESSION['UsuarioID'])) {
					     echo'<a href="?tela=login">Login</a>';
					}else{
					 	$n_acesso = $_SESSION['UsuarioAcesso'];
					    print '<br><div id="menu-login">
					    		Olá, ';
					    print $_SESSION['UsuarioNome'];
					    if ($n_acesso > 1) {
					    	print '<span class="dropdown"><a href="" title="Meu Menu"><img src="intra/images/menu.png"';
					    ?>
					     	onMouseOver="this.src='intra/images/menu-hover.png'"
							onMouseOut="this.src='intra/images/menu.png'"
					    <?php
					    	print'></a><span class="dropdown-content">';
    						menuAcesso($n_acesso);
  							print '</span></span>';
					    }
					     	print'<a href="?tela=config" title="Meu Perfil"><img src="intra/images/conf.png"';
					    ?>
					    	onMouseOver="this.src='intra/images/conf-hover.png'"
							onMouseOut="this.src='intra/images/conf.png'"
					    <?php
					    	print '></a><a href="intra/inc/sair.php" title="Sair"><img src="intra/images/sair.png"';
					    ?>
					     	onMouseOver="this.src='intra/images/sair-hover.png'"
							onMouseOut="this.src='intra/images/sair.png'"
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
		<div style="text-align: center; margin: 15px; padding: 10px; border: 3px solid #d8d8d8;" >
			<img src="intra/images/aviso.png">
			<h1>Rede Instável de TI</h1>
			<p>Estamos com problemas técnicos. Já está sendo verificada uma solução, mas ainda não há previsão de retorno Normal dos Sistemas. Rede de TI ao Datacenter funcionando através de Contingência com menor desempenho.</p>
		</div>
		<div id="conteudo"> 	
			<!-- Switch de Funções -->
		<?php
		$tela =  $_GET['tela'];
		switch ($tela) {
		  case "convenio":
				print '<div id="titulo" class="cor-padrao">Convênios</div>';
			//print '<div id="conteudo">';
				funcaoConvenios($mysqli);
		    break;
		  case "ramais":
		  	print '<div id="titulo" class="cor-padrao">Ramais</div>';
			//print '<div id="conteudo">';
			funcaoRamais($mysqli);
		    break;
		  case "sug_ramal":
		  	print '<div id="titulo" class="cor-padrao">Sugerir Ramal</div>';
			//print '<div id="conteudo">';
		    funcaoSugRamal($mysqli);
		    break;
		  case "restrita":
		    print '<div id="titulo" class="cor-padrao">Area Restrita</div>';
			//print '<div id="conteudo">';
		    funcaoAreaRestrita($mysqli);
		    break;
		  case "documentos":
		    print '<div id="titulo" class="cor-padrao">Documentos</div>';
			//print '<div id="conteudo">';
		    funcaoDocumentos($mysqli,$n_acesso);
		    break;
		  case "pots":
		    print '<div id="titulo" class="cor-padrao">POTS</div>';
			//print '<div id="conteudo">';
		    funcaoPots();
		    break;
		  case 'arquivos':
		    print '<div id="titulo" class="cor-padrao">Arquivos</div>';
			//print '<div id="conteudo">';
		    funcaoArquivosPastas();
		    break;
		  case 'config':
		    print '<div id="titulo" class="cor-padrao">Meu Perfil</div>';
			//print '<div id="conteudo">';
		    funcaoConfig();
		    break;
		  case 'confEdt':
		    print '<div id="titulo" class="cor-padrao">Configurar</div>';
			//print '<div id="conteudo">';
		    funcaoConfigEdt();
		    break;
		  case 'pesquisas':
		    print '<div id="titulo" class="cor-padrao">Pesquisas</div>';
			//print '<div id="conteudo">';
		    funcaoPesquisas($mysqli);
		    break;
		  case 'login':
		    print '<div id="titulo" class="cor-padrao">Login</div>';
			//print '<div id="conteudo">';
		    funcaoLogin($mysqli);
		    break;
		
		  default:
		  	print '<div id="titulo" class="cor-padrao">Home</div>';
		    funcaoAtalhos($mysqli);
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