function lookup(nomeCampoForm, nomeTabela, nomeCampoChave, nomeCampoExibicao, nomeCampoAuxiliar, titulo, largura) {
	if (largura == null) {
		largura = 250;
	}
	newWindow = window.open('../inc/lookup.php?nomeCampoForm=' + nomeCampoForm
	          + '&nomeTabela=' + nomeTabela
				 + '&nomeCampoChave=' + nomeCampoChave
				 + '&nomeCampoExibicao=' + nomeCampoExibicao
				 + '&nomeCampoAuxiliar=' + nomeCampoAuxiliar
				 + '&titulo=' + titulo,
				 'newWin',
				 'toolbar=no,location=no,scrollbars=yes,resizable=no,width='+largura+',height=520,top=35,left=25');
}
