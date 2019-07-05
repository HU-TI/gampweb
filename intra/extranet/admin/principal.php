<?
/*
	Montagem dos frames da aplicação, não deve ser alterada
*/
include("../inc/common.php");
$conn = new db();
$conn->open();
?>
<html>
<head>
<title><?=SIS_TITULO?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link rel="stylesheet" type="text/css" href="../css/portal.css">
</head>
<body class=BODY>
<div align=left>
<table class="corpo" border=0 cellpadding=0 cellspacing=0>
	<tr valign=top>
		<td width="20%" class=menu align="right">
			<TABLE width="100%">
				<TR>
					<TD><H1><nobr>Pacto RS</nobr></H1></TD>
				</TR>
				<TR>
					<TD><BR><? include "menu.inc.php"; ?></TD>
				</TR>
			</TABLE>			
		</td>
		<td class=conteudo width="80%">
			<div align=center>
					<table class=conteudo width=80% border=0 cellspacing=0 cellpadding=0>
					   <tr>
							<td><?
							$menu = getParam("menu");
							$cod = getParam("cod");
							switch ($menu) {
								case "apresentacao":
									include "apresentacao.inc.php";
								break;
								case "doc":
									include "documentacao.inc.php";
								break;
								case "enquete":
									include "enquete.inc.php";
								break;
								case "evento":
									include "evento.inc.php";
								break;
								case "eventodet":
									include "eventodet.inc.php";
								break;
								case "fale":
									include "fale.inc.php";
								break;
								case "inscricao":
									include "inscricao.inc.php";
								break;
								case "link":
									include "link.inc.php";
								break;
								case "metodologia":
									include "metodologia.inc.php";
								break;
								case "noticia":
									include "noticia.inc.php";
								break;
								case "noticiadet":
									include "noticiadet.inc.php";
								break;
								case "participantes":
									include "participantes.inc.php";
								break;
								case "programacao":
									include "programacao.inc.php";
								break;
								default:
								include "noticia.inc.php";
							}
							?>
							<hr>
							<B>Enquetes</B>
							<BR>
							<SCRIPT LANGUAGE="JavaScript">
							<!--
							function testa_voto(frm){
								// testa se a entrada é válida
								campo = frm.resposta;
								voto = 0;
								for (i=0;i<campo.length;i++)
									if (campo[i].checked){ voto = 1; resp = campo[i].value;}
								if (voto == 0){
									alert("Informe seu voto!");
									return false;
								}
								return true;
							}
							function testa_voto(frm){
								// testa se a entrada é válida
								campo = frm.resposta;
								voto = 0;
								for (i=0;i<campo.length;i++)
									if (campo[i].checked){ voto = 1; resp = campo[i].value;}
								if (voto == 0){
									alert("Informe seu voto!");
									return false;
								}
								return true;
							}
							//-->
							</SCRIPT>
					<?
					$conn = mysql_pconnect("pactors.mysql-teste.procergs","pactors","tr66ew");
				$db = mysql_select_db("pactors");	
				$sql = "SELECT * FROM enquete WHERE publicada = 'S' Order by cod_enquete desc";
				$result = mysql_query($sql,$conn);
				$tot_rows = mysql_num_rows($result);
				if ($tot_rows == 0){
				?>
                   <br><br>Não há enquetes disponíveis no momento!<br><br><br>
      				<?
						} else {
							$row = mysql_fetch_array($result);
							$enq = $row['cod_enquete'];	?>
                              <span class="texto1"><?=$row["pergunta"]?></span>
					     <?
							$sql = "SELECT * FROM enqueteResposta ".
                            "WHERE cod_enquete = ".$enq." ".
                            "ORDER BY cod_resposta";
							$rs = mysql_query($sql,$conn);
							$t_rows = mysql_num_rows($rs);
							if ($t_rows == 0){
						 ?>
								<br>Não há respostas cadastradas!<br><br>
						<?	} else {	?>
      							<form name="form_enquete" action="javascript:abre_enquete('enquete/resultado.php?acao=voto&cod_enquete=<?=$enq?>&resposta=<?=getParam("resposta")?>','Enquete',400,350);" method="post" onsubmit="return testa_voto(this);">
								<?	while ($row = mysql_fetch_array($rs)) {	?>
                                      <nobr><input type="radio" name="resposta" value="<?echo $row['cod_resposta'];?>">
                                       <?echo $row['resposta'];?></nobr><BR>
								<?	} ?>
						 <br>
                          <input type="Image" src="../img/enviar.gif" border="0" alt="Buscar">				  
                          </form>
                        <a href="javascript:abre_enquete('enquete/resultado.php?acao=resultado&cod_enquete=<?=$enq?>','Enquete',400,350);">resultado parcial</a>
						<?	}
							mysql_free_result($rs);
						}
						mysql_free_result($result);	?>
						</td>
					</tr>
				</table>
					</div>
			</td>
		</tr>
</table>
</div>
</body>
</html>