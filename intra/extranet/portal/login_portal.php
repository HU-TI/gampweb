<?
/*
	Esta página exibe o formulário de login
*/
//include("../inc/common.php");

/*$ret_page = getParam("ret_page");
$querystring = getParam("querystring");
if (!$ret_page) {
	$label_botao = "Entrar";
} else {
	$label_botao = "Continuar";
}*/
/*
$button = new Button;
$button->addItem(" Retornar ","JavaScript:history.go(-2)");
$button->addItem($label_botao,"JavaScript:document.frm.submit()");

$form = new Form_Portal("frm", "index.php?menu=login_validar_portal", "POST", "300");
$form->setLabelWidth("50%");
$form->setDataWidth("50%");

$form->addHidden("rodou","s");
$form->addHidden("ret_page",$ret_page);
$form->addHidden("querystring",$querystring);

$form->addField("Nome do usuário:", textField("f_username",getSession("sis_username_antigo"),20,50));
$form->addField("Senha:",passwordField("f_senha","",20,50));
$form->addBreak($button->writeHTML(),false);
*/
?>
	<script language="JavaScript">
	function inicializa() {
		if (document.captureEvents && Event.KEYUP) {
			document.captureEvents( Event.KEYUP);
		}
		document.onkeyup = trataEvent;
		
		// inicia o foco no primeiro campo
		user = document.frm.f_username.value;
		if (user.length > 0) {
			document.frm.f_senha.focus();
		} else {
			document.frm.f_username.focus();
		}
	}
	
	// tratamento para capturar tecla enter
	function trataEvent(e) {
		//verifica se é IE
		if( !e ) {
			if( window.event ) {
				e = window.event;
			} else {
				//falha, não tem como capturar o evento
				return;
			}
		}
		if( typeof( e.keyCode ) == 'number'  ) {
			//IE, NS 6+, Mozilla 0.9+
			e = e.keyCode;
		} else {
			//falha, não tem como obter o código da tecla
			return;
		}
		if (e==13) {
			document.frm.submit();
		}
	}
	</script>
<br><br>
<p align="center" class="titulo"></p>
<br>
<?
//echo $form->writeHTML();
?>
<span style='padding-left:30px' class='titulo2'>Login</span>
<FORM METHOD="POST" ACTION="index.php?menu=login_validar_portal">
	
		<table align=center width=540 cellpadding=0 border=0 cellspacing=0 bgcolor="#EFEFEF" style="border:1px dotted blue">
		<tr>
			<td align=center calign="middle" border=1>
				<BR><BR>
				<TABLE style="border:1px solid dotted" class="textointerno" align=center cellpadding=0 cellspacing=0 bgcolor="#efefef" border="0">		
		<TR>
			<TD class="texto_preto" align="left"><nobr><?=htmlentities("Usuário:")?></nobr></TD>
			<TD align="left">
				<INPUT TYPE="text" NAME="f_username" size="30"  maxlength="60"><!-- class="input" -->
			</TD>
		</TR>		
		<TR>
			<TD class="texto_preto" align="left"><nobr>Senha:</nobr></TD>
			<TD align="left">
				<INPUT TYPE="password" NAME="f_senha" size="30"   maxlength="60"><!-- class="input" -->
			</TD>
		</TR>
		<TR>
			<TD></TD>
			<TD align="right">
				<input type="image" name="enviar" src="../img/bot_enviar.gif"  onmouseover="this.src='../img/bot_enviar_over.gif'" onmouseout="this.src='../img/bot_enviar.gif'">
			</TD>
		</TR>	
		<TR>
			<TD><BR></TD>
			<TD align="right">
				
			</TD>
		</TR>
		</FORM>
	</TABLE>
	</td>
	</tr>
</table>
