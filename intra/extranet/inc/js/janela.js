function abreJanelaAuxiliar(pagina){
	eval('janela = window.open("../inc/auxiliar.php?pag=' +  pagina +
	     '","janela","width=600,height=500,top=50,left=150' +
		  ',scrollbars=no,hscroll=0,dependent=yes,toolbar=no")');
	janela.focus();
}