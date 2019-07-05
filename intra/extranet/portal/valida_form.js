<!--//
var FLAG_LABEL = '%label%';

var erro;							//variável global q guarda o status do erro
var customMsg = '';					//mensagem de erro personalizada
var	enableOnSubmit = true;			//validaform deve habilitar campos disabled ao submeter?
var vFormEnabled = new Array();
var joinMessages = false;
var strMessages = '';
var lastField = false;
var frm;

function validaForm(nomeform, vetor, customMessage){

	//os parametros passados para a funcao: nome_do_campo|tipo_de_validacao|"label"_para_campo. 
	//Para validar mais de um campo, passa-los separados por virgulas.
	//O parâmetro customMessage serve para alerts personalizados
	strMessages = '';
	var i, campos, params;
	if ( customMessage ) {
		customMsg = customMessage;
	}

	if(vetor.indexOf(",") == -1) {
		campos = vetor;
		lastField = true;
		getParams(nomeform, campos);

	} else {
		tmp = vetor.split(",");
		campos = new Array();
		for (i=0; i < tmp.length; i++) {
			if (tmp[i]) {
				campos.push(tmp[i]);
			}
		}

		for (i=0; i < campos.length; i++) {
			if (campos[i]) {
				lastField = (i == (campos.length-1) ? campos[i] : "");
				getParams(nomeform, campos[i]);
				if ( erro && !joinMessages) {
					break;
				}
			}
		}
	}

	// Força msg de erro quando msgs são agrupadas mas o ultimo campo não gera erro
	if ( !erro && strMessages) {
		errorMsg();
		erro = true;
	}

	if ( !erro && enableOnSubmit ) {
		enableAll(frm);
	}
	return !(erro);
}



/************************************************************************************************
*  Responsável pela chamada de cada um dos tipos de validação									*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function getParams(nomeForm, campos) {

	var params = campos.split("|");
	var tipoValidacao = params[1].toUpperCase();
	frm = document.forms[nomeForm];
	var field = frm.elements[params[0]];
	//alert(params[0] +'\n'+ field);
	var label = params[2];
	var disabled = !isEnabled(field);

	if (!disabled) {

		switch (tipoValidacao) {

			case 'ISNULL':
				//Valida se o campo está em branco
				if ( (field.length && !field.type) || field.type == 'checkbox' ) {
					xNull(field, label);
				} else {
					isNull(field, label);
				}
				break;

			case 'ISXNULL':
				//Valida radios e checkboxes
				xNull(field, label);
				break;

			case 'ISEMAIL':
			case 'VT_MAIL':
				//Valida se o campo contém um e-mail válido
				isEmail(field, label);
				break;

			case 'ISPASSWORD':
			case 'VT_PASSWORD':
				//Valida se dois campos contém o mesmo valor
				field2 = eval(nomeForm + '.' + params[2]);
				compareFields(field, field2);
				break;

			case 'ISNUMBER':
			case 'ISNUMERIC':
			case 'VT_NUMBER':
				//Valida se o campo contém um valor numérico
				isNumeric(field, label);
				break;

			case 'ISNATURAL':
			case 'VT_NATURAL':
				//Valida se o campo contém um valor positivo
				isNatural(field, label);
				break;

			case 'LEN':
				//Valida se o length do campo é igual ao parâmetro "requiredLen" da tag <input>
				Len(field, label);
				break;

			case 'ISCPF':
			case 'VT_CPF':
				//Valida se é um cpf válido
				isCPF(field.value);
				if (erro) setFocus(field);
				break;

			case 'ISCNPJ':
			case 'ISCGC':
			case 'VT_CNPJ':
				//Valida se é um cgc válido
				erro = !isCGC(field.value);
				if (erro) setFocus(field);
				break;

			case 'ISTIME':
			case 'VT_TIME':
				//Valida se é um horário no formato hh:mm
				isTime(field, label);
				break;

			case 'ISDATE':
			case 'VT_DATE':
				//Valida se é uma data no formato dd/mm/aa
				isDate(field, label);
				break;

			case 'MAXLEN':
				//MaxLength(para textareas)
				maxLen(field, label);
				break;

			case 'ISRG':
				//Valida se é um rg válido
				isRG(field.value);
				break;

			case 'ISCEP':
			case 'VT_CEP':
				//Valida se é um cpf válido
				isCEP(field,label);
				break;
			
			case 'ISWORD':
			case 'VT_WORD':
				//Valida campos de username
				isWord(field, label);
				break

			case 'ISGREATER':
			case 'VT_GREATER':
				//Verifica se o valor campo eh maior do q determinado valor
				isGreater(field, label, params[3]);
				break;
		}
	}
}





/************************************************************************************************
*  Habilita todos os campos antes do submit do form												*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 06/02/2002 - 14:53																*
************************************************************************************************/
function enableAll(oForm) {
	for (z=0; z < oForm.elements.length; z++) {
		if ( oForm.elements[z].disabled ) {
			vFormEnabled[vFormEnabled.length] = oForm.elements[z];
			oForm.elements[z].disabled = false;
		}
	}
}


/************************************************************************************************
*  Desabilita todos os campos habilitados por enableall											*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 06/02/2002 - 14:53																*
************************************************************************************************/
function rollback() {
	for (i=0; i<vFormEnabled.length; i++) {
		vFormEnabled[i].disabled = true;
	}
}



/************************************************************************************************
*  Verifica se um campo estah disabled ou nao													*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 11/09/2003 - 14:16																*
************************************************************************************************/
function isEnabled(field) {
	if ( field.length && !field.type ) {
		for (var i=0; i<field.length; i++) {
			if ( !field[i].disabled ) {
				return true;
			}
		}
		return false;
	} else {
		return !field.disabled;
	}
}


/************************************************************************************************
*  Maxlength para campos textarea																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function maxLen(campo, alias) {

	var msg;
	erro = false;

	if (campo.value.length > campo.maxlength) {
		erro = true;
		msg = "O campo '"+ alias +"' deve conter, no máximo, "+ campo.maxlength +" caracteres!";
		errorMsg(campo, alias, msg);
	}
	return !erro;

}



/************************************************************************************************
*  Validação de campos username																	*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 30/01/2003 - 17:44																*
************************************************************************************************/
function isWord(campo, alias) {

	erro = false;
	var re = /^[a-zA-Z_][a-zA-Z_0-9]+$/;
	var msg;

	if ( campo.value.match(re) == null ) {
		erro = true;
		msg = "O campo '"+ alias +"' não deve conter espaços!";
		errorMsg(campo, alias, msg);
	}
	return !erro;

}


/************************************************************************************************
*  Comparação se campos senha																	*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function compareFields(field1, field2, msg) {
	erro = false;
	if (!msg) msg = 'As senhas digitadas não conferem!';

	if (field1.value != field2.value) {
		erro = true;
		alert(msg)
		setFocus(field2);
	}
	return !erro;

}




/************************************************************************************************
*  auto-complete para campos de CEP																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function setCep() {

	var ignoreKeys = Array(8,9,13,16,17,35,36,37,39,46);
	var fld = window.event.srcElement;
	var frm = fld.form;
	var str = fld.value.replace(/\-/g,'');

	if (str) {
		if ( !inArray(ignoreKeys, window.event.keyCode) ) {
			if (str.length == 5)
				str += '-';
			else if (str.length > 5)
				str = str.substr(0,5) +'-'+ str.substr(5,3);
			fld.value = str;
			if (str.length == 9) focusNext(fld);
		}
	}
}



/************************************************************************************************
*  Verifica a existencia de um valor em um array												*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 20/11/2001 - 12:07																*
************************************************************************************************/
function inArray(theArray, theValue) {
	for (var i=0; i<theArray.length; i++) {
		if ( theArray[i].toString().toLowerCase() == theValue.toString().toLowerCase() )
			return true;
	}
	return false;
}





/************************************************************************************************
*  Validação de CEP																				*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isCEP(campo, alias){

	var str;
	var msg;
	erro = false;
	str = trim(campo.value);

	if (str) {
		var re = /^[0-9]{8}$/ig;
		if ( !str.match(re) ) {

			erro = true;
			msg = "O campo '"+ alias +"' não contém um CEP válido!";
			errorMsg(campo, alias, msg);

		}
	}
	return !erro;
}





/************************************************************************************************
*  Validação de CGC																				*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isCNPJ(cnpj) {
	return isCGC(cnpj);
}



function isCGC(cgc) {

	var calcCGC;
	var s1;
	var s2;
	var i;
	var soma;
	var digito;
	var result;

	s1 = cgc;
	result = false;

	if (s1.length != 14) {
		alert('O CNPJ deve possui 14 dígitos!');
		return result;
	}
	if (s1 == '00000000000000') {
		alert('CNPJ inválido!');
		return result;
	}	
	calcCGC = s1.substring(0, 12);

	//  Cálculo do 1º dígito 
	soma = 0;
	for (i = 1; i <= 4; i++) {
		soma = soma + (calcCGC.charAt(i-1) * (6 - i));
	}
	for (i = 1; i <= 8; i++) {
		soma = soma + (calcCGC.charAt(i + 3) * (10 - i));
	}
	digito = 11 - (soma % 11);

	if ((digito == 10) || (digito == 11))
		calcCGC = calcCGC + '0';
	else
		calcCGC = calcCGC + digito;

	// Cálculo do 2º dígito 
	soma = 0;
	for (i = 1; i <= 5; i++) {
		soma = soma + (calcCGC.charAt(i-1) * (7 - i));
	}
	for (i = 1; i <= 8; i++) {
		soma = soma + (calcCGC.charAt(i + 4) * (10 - i));
	}
	digito = 11 - (soma % 11);

	if ((digito == 10) || (digito == 11))
		calcCGC = calcCGC + '0';
	else
		calcCGC = calcCGC + digito;

	if (calcCGC == s1) result = true;
	
	if (!result) alert('CNPJ Inválido!');

	return result;
}






/************************************************************************************************
*  Validação de CPF																				*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isCPF(cpf) {

	var i;
	var soma;
	var digito;
	var calcCPF;
	var s1;
	var s2;
	var b;
	var c;
	var result;
	var alerted;

	erro = alerted = false;

	s1 = cpf;
	if (s1.length != 11) {
		alert('O CPF deve possuir 11 dígitos');
		erro = true;
		alerted = true;
		return;
	}
	// Teste se os 11 díg. são iguais 
	b = true;
	c = s1.charAt(0);
	for (i = 1; i < 11; i++) { 
		if ((b) && (s1.charAt(i) == c))
			b = true;
		else
			b = false;
		c = s1.charAt(i);
	}
	
	if (b) {
		erro = true;
	}

	calcCPF = s1.substring(0, 9);
	// Cálculo do 1º dígito
	soma = 0;
	for (i = 1; i <= 9; i++) {
		soma = soma + (calcCPF.charAt(i-1) * (11 - i));
	}
	digito = 11 - (soma % 11);

	if ((digito== 10) || (digito == 11))
		calcCPF = calcCPF + '0';
	else
		calcCPF = calcCPF + digito;

	// Cálculo do 2º dígito
	soma = 0;
	for (i = 0; i <= 10; i++) {
		soma = soma + (calcCPF.charAt(i-1) * (12 - i));
	}
	digito = 11 - (soma % 11);

	if ((digito== 10) || (digito == 11))
		calcCPF = calcCPF + '0';
	else
		calcCPF = calcCPF + digito;

	if (calcCPF == s1)
		result = true;
	else 
		result = false;

	if (!erro)
		erro = !result;
	
	if (erro && !alerted)
		alert('O CPF digitado é inválido!');

}





/************************************************************************************************
*  Validação de data																			*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isDate( campo , alias) {

	var msg;
	var str = campo.value;
	erro = true;
	arrMonths = new Array(0,31,28,31,30,31,30,31,31,30,31,30,31);
	var re = /^[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{4}$/ig;

	if ( str.match(re) ) {

		aDate = str.split('/');
		day = aDate[0]*1;
		month = aDate[1]*1;
		year = aDate[2]*1;

		if (isLeap(year)) arrMonths[2] = 29;

		if ( (day > 0) && (day <= arrMonths[month]) && (month > 0) && ( month < 13) )
			erro = false;

	} else if ( !str )
		erro = false;


	if (erro) {
		msg = 'O campo '+ alias +' deve conter uma data no formato DD/MM/AAAA';
		errorMsg(campo, alias, msg);
	}
	return !erro;
}






/************************************************************************************************
*  Verifica se eh ano bissexto (usada na funcao isDate)											*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isLeap(y) {
	var N = parseInt(y);
	return ( ( N%4==0 && N%100 !=0 ) || ( N%400==0 ));
}





/************************************************************************************************
*  Validação de e-mail																			*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isEmail(campo, alias) {

	var c;
	var msg;
	erro = false;
	strMail = campo.value;

	if (strMail) {

		//Verifica a existência de "@" e se existe alguma string antes
		if(strMail.search("@")>=1) {

			//Verifica se existe algum caracter inválido
			strMail = strMail.replace("@",".");
			arrMail = strMail.split(".");
			if(arrMail.length<3) erro = true;

			for(c=0; c<arrMail.length; c++) {
				if(erro) 
					break;
				else
					erro = hasWeirdChars(arrMail[c]);
				if ( !arrMail[c].toString().replace(/ /g, '') )
					erro=true;
			}
		}
		else
			erro = true;
	}

	if (erro) {
		msg = "O campo '"+ alias +"' não contém um e-mail válido!";
		errorMsg(campo, alias, msg);
	}
	return !erro;
}






/************************************************************************************************
*  Verifica existencia de caracteres inválidos em um e-mail(usada em isEmail)					*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function hasWeirdChars(str) {

	var A_Z = "0123456789aAbBcCdDeEfFgGhHiIjJkKlLmMnNoOpPqQrRsStTuUvVwWxXyYzZ";
	var others = A_Z + "-_";
	var i;
	var last = str.length-1;

	for (i=0;i<str.length;i++) {
		chrNow = str.charAt(i);
		if ( (i==0) || (i==last) ) {

			if ( (A_Z.indexOf(chrNow)==-1) )
				return true;

		} else {

			if (others.indexOf(chrNow)==-1)
				return true;
		}
	}
	return false;
}





/************************************************************************************************
*  Validação de campos obrigatórios																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isNull(campo, alias){

	erro = false;
	var auxText = trim(campo.value)
	var msg;

	if (auxText.length == 0) {

		erro = true;
		msg = "O campo '"+ alias +"' deve ser preenchido obrigatoriamente!";
		campo.value = auxText;
		errorMsg(campo, alias, msg);

	}
	return !erro;
}



/************************************************************************************************
*  Trim																							*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function trim(str) {
	return str.replace(/^\s+/g, '').replace(/\s+$/g, '');
}




/************************************************************************************************
*  validação de campos numéricos																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isNumeric(campo, alias) {

	erro = false;
	var msg;
	var n = campo.value.replace('.','').replace(',', '.');

	if ( isNaN(n) ) {
		erro = true;
		msg = "O campo '"+ alias +"' deve conter um valor numérico!";
		errorMsg(campo, alias, msg);
	}
	return !erro;
}




/************************************************************************************************
*  validação de campos numéricos positivos														*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 14/01/2003 - 16:41																*
************************************************************************************************/
function isNatural(campo, alias) {

	erro = false;
	var msg;

	var n = campo.value.replace(',','.')*1;
	if ( !(n > 0) && campo.value) {
		erro = true;
		msg = "O campo '"+ alias +"' deve conter um valor positivo!";
		errorMsg(campo, alias, msg);
	}
	return !erro;

}



/************************************************************************************************
*  Verifica se o valor de um campo eh maior q determinado valor									*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 04/09/2003 - 11:57																*
************************************************************************************************/
function isGreater(campo, alias, value) {

	erro = false;
	var msg;
	var n = campo.value.replace(',', '.')*1;
	v2 = ( isNaN(value.replace(',', '.') * 1) ? campo.elements[value] : value.replace(',', '.')*1 );
	if ( campo.value && n <= v2 ) {
		erro = true;
		msg = "O valor do campo '"+ alias +"' deve ser maior que "+ v2.toString() +"!";
		errorMsg(campo, alias, msg);
	}
	return !erro;

}



/************************************************************************************************
*  Validação de campos de hora																	*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function isTime(campo, alias){
	
	erro = true;
	var msg;
	var hour = campo.value;

	if ( campo.value ) {

		if(hour.indexOf(":")) {
			arrParts = hour.split(":")
			if(arrParts.length == 2){
				hora = (arrParts[0]*1);
				mins = ('0'+ arrParts[1]*1);
				if(!(isNaN(hora)) && !(isNaN(mins))){
					if((hora<24)&&(hora>=0)) {
						if( (mins<60) && (mins>=0) ){
							erro = false;
							campo.value = right('00'+ hora, 2) +':'+ right('00'+ mins, 2);
						}
					}
				}
			}
		}

	} else {
		erro = false;
	}

	if (erro) {
		if (alias) msg = "O campo '"+ alias +"' deve conter um horário válido!";
		errorMsg(campo, alias, msg);
	}

	return !erro;
}




/************************************************************************************************
*  Validação de radios e checkboxes																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function xNull(campo, alias) {

	erro = true;
	var multiple = false;
	if (campo.length) {
		multiple = (campo[0].type != 'radio');
		for (var i=0; i<campo.length; i++) {
			if (campo[i].checked) {
				erro = false;
				break;
			}
		}
	} else if (campo.checked) {
		erro = false;
	}

	if (erro && alias) {
		msg = (multiple ? "Você deve selecionar pelo menos uma opção no campo '"+ alias +"'." : "O campo '"+ alias +"' deve ser preenchido obrigatoriamente!");
		errorMsg(campo, alias, msg);
	}

	return !erro;
}




/************************************************************************************************
*  Tamanho mínimo de um campo																	*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 10/05/2001 - 13:41																*
************************************************************************************************/
function Len(campo, alias) {

	erro = false;
	var msg;

	if (campo.value.length < campo.requiredLen) {

		erro = true;
		msg = "O campo '"+ alias +"' deve conter, no mínimo, "+ campo.requiredLen +" caracteres!";
		errorMsg(campo, alias, msg);

	}
	return !erro;
}



/************************************************************************************************
*  Exibe mensagem de erro																		*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 11/12/2002 - 14:40																*
************************************************************************************************/
function errorMsg(oFld, alias, strMsg) {
	if ( strMsg || !erro) {
		var customized = (customMsg ? customMsg.replace(FLAG_LABEL, alias) : strMsg);
		if ( joinMessages ) {
			strMessages += (strMessages && customized ? '\n' : '') + (customized ? customized : '');
			if ( lastField ) {
				alert(strMessages);
			}
		} else {
			alert(customized);
			setFocus(oFld);
		}
	}
}



/************************************************************************************************
*  Seta o foco no campo onde ocorreu o erro														*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 11/12/2002 - 14:40																*
************************************************************************************************/
function setFocus(oFld) {
	try {
		var strType = oFld.type.toLowerCase();
		if ( strType == 'text' ) oFld.select();
		if ( strType == 'password') oFld.select();
		if ( strType != 'hidden' ) oFld.focus();
	} catch (err) {
		return false;
	}
}


/************************************************************************************************
*  auto-complete para campos de Hora															*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 26/02/2003 - 18:49																*
************************************************************************************************/
function setTime(fld, autoComplete) {

	var ignoreKeys = Array(8,9,13,16,17,35,36,37,39,46);
	if (!fld) fld = window.event.srcElement;
	var frm = fld.form;
	var str = fld.value.replace(/[^\d]/g,'');
	if (autoComplete) fld.onblur = setTimeComplete;

	if (str) {
		if ( !inArray(ignoreKeys, window.event.keyCode) ) {
			if (str.length == 2)
				str += ':';
			else if (str.length > 2)
				str = str.substr(0,2) +':'+ str.substr(2,2);
			fld.value = str;
			if (str.length == 5) focusNext(fld);
		}
	} else fld.value = '';
}



/************************************************************************************************
*  auto-complete para campos de Hora															*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 26/02/2003 - 18:49																*
************************************************************************************************/
function setTimeComplete(fld) {
	if (!fld) fld = window.event.srcElement;
	str = fld.value;
	if (str) {
		tmp = str.split(':');
		str = right('00'+ tmp[0], 2);
		str += ':'+ right('00'+ (tmp.length == 2 ? tmp[1] : ''), 2);
		fld.value = str;
	}
}



/************************************************************************************************
*  auto-complete para campos de Data															*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 26/02/2003 - 18:49																*
************************************************************************************************/
function setDate(fld, autoComplete) {
	var ignoreKeys = Array(8,9,13,16,17,35,36,37,39,46);
	str = fld.value.replace(/\D/g,'');
	if (str) {
		if ( !inArray(ignoreKeys, window.event.keyCode) ) {
			var s1 = str.substr(0,2);
			var s2 = str.substr(2,2);
			var s3 = str.substr(4,4);
			str = s1 +(s1.length==2 ? '/' : '') + s2 + (s2.length==2 ? '/' : '') + s3;
			fld.value = str;
			if ( autoComplete ) {
				setDateComplete(fld);
				str = fld.value;
			}
			/*if ( str.match(/^\d{2}\/\d{2}\/\d{4}$/) ) {
				focusNext(fld);
			}*/
		}
	}
}



/************************************************************************************************
*  auto-complete para campos float																*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 29/08/2003 - 10:32																*
************************************************************************************************/
function setFloat(campo, precision) {

	if ( !campo ) {
		campo = window.event.srcElement;
	}
	if ( !precision || isNaN(precision) ) {
		precision = 2;
	}

	if ( !campo.onkeypress ) {
		campo.onkeypress = stripNaN;
	}

	var ignoreKeys = Array(9, 13, 16, 17, 35, 36, 37, 39, 46);
	var tammax = 18;
	var tecla = window.event.keyCode;

	if ( !inArray(ignoreKeys, tecla) ) {

		vr = campo.value.replace(/(\/|\.|\,)/g, '');
		tam = vr.length;

		if (tam < tammax && tecla != 8) {
			tam = vr.length + 1 ;
		} else if ( tecla == 8 ) {
			tam -= 1 ;
		}

		if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ) {

			if ( tam <= precision) {
				campo.value = vr;
			} else {
				s = '';
				init = tam - precision - 1;
				for (var i = init; i >= 0; i--) {
					s = vr.charAt(i) + s;
					if ( ((init-i+1) % 3 == 0) && (i != 0) ) {
						s = '.'+ s;
					}
				}
				s += ','+ vr.substr(init + 1);
				campo.value = s;
			}
		}

		if ( tam >= tammax ) {
			window.event.returnValue = false;
		}
	}
}



/************************************************************************************************
*  aceita apenas valores numericos. deve ser usada em onkeypress								*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 29/08/2003 - 10:32																*
************************************************************************************************/
function stripNaN(objeto) {
	if ( !objeto ) {
		campo = window.event.srcElement;
	} else {
		campo = eval (objeto);
	}

	var ignoreKeys = Array(9, 13, 16, 17, 35, 36, 37, 39, 46);
	keyPress = window.event.keyCode;
	chr = String.fromCharCode(keyPress);
	conjunto1 = 12;
	if ( chr.match(/^\d$/) && campo.value.length < (conjunto1) ){
		if (campo.value.length == conjunto1) {
			campo.value = campo.value;
		}
	} else if ( !inArray(ignoreKeys, keyPress) ) {
		window.event.returnValue = false;
	}
}




/************************************************************************************************
*  auto-complete para campos de Data															*
*  Autor: Carlos Eduardo Maciel																	*
*  Data/Hora: 26/02/2003 - 18:49																*
************************************************************************************************/
function setDateComplete(fld) {
	if (!fld) fld = window.event.srcElement;
	str = fld.value;
	var re = /^\d{2}\/\d{2}\/\d{2}$/i;
	if ( str.match(re) )
		fld.value = str.replace(/(\d{2})$/i, '20$1');
}



//Seta o foco no próximo campo
function focusNext(fld) {
	var frm = fld.form;
	for (var i=0; i<frm.elements.length; i++) {
		if (frm.elements[i].name == fld.name && i+1<=frm.elements.length)
			setFocus(frm.elements[i+1]);
	}
}


function right(str, strlen) {
	return str.substr(str.length-strlen, strlen);
}


function left(str, len) {
	return str.substr(0,len);
}
//-->