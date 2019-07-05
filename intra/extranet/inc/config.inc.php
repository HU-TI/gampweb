<?
// Identificador da aplicação
define("SIS_APL_NAME","Clínica Reequilíbrio Extranet");
define("SIS_APL_EMAIL","angelo.rigo@gmail.com");
define("SIS_APL_URL","http://www.reequilibrio.com.br/extranet");



// Dados da aplicação
define("SIS_TITULO","Clínica Reequilíbrio Extranet");
define("SIS_VERSAO","versão 20081020");
define("SIS_NOME_RESPONSAVEL","Ângelo Rigo");
define("SIS_EMAIL_RESPONSAVEL","angelo.rigo@gmail.com");
define("SIS_FULLSCREEN",false);

// Conexão com o banco de dados
define("DB_DATABASE","reequi_extrane");
define("DB_HOST","186.202.152.244");
define("DB_USER", "reequi_extrane");
define("DB_PASSWORD","reeq@2016");
define("DB_PERSISTENT",false);

// Dados sobre a autenticação de usuário
define("AUTH_TABELA","usuario");
define("AUTH_ID","cod_usuario");
define("AUTH_USERNAME","usuario");
define("AUTH_PASSWORD","senha");
define("AUTH_LEVEL","nivel_acesso");
define("AUTH_CRIPT",true); // MD5

// Página de login
define("LOGIN_TITULO","Acesso Restrito");

// Página de acesso negado
define("LOGIN_ACESSONEGADO","Você não tem permissão para<br>acessar esta página");

// Dados sobre a janela de lookup
define("LOOKUP_MAX_REC",300);
define("LOOKUP_TITULO_PESQUISA","Localizar:");
define("LOOKUP_SUBTITULO","Selecione o registro abaixo");
define("LOOKUP_RESET","&raquo; Inserir vazio ");
define("LOOKUP_FIELDSIZE",40);
define("LOOKUP_IMAGEM","../img/smallsearch.gif");

// Altura do frame de controle
define("FRAME_CONTROLE_ALTURA","0"); // utilizar em produção
#define("FRAME_CONTROLE_ALTURA","50"); // utilizar durante o desenvolvimento

// Altura do frame de cabeçalho
define("FRAME_HEADER_ALTURA","30");

// Largura do frame de menu
define("FRAME_MENU_LARGURA","180");

// Definição dos estilos
define("CSS_HEADER","../css/header_yahoo.css");
define("CSS_MENU","../css/menu_yahoo.css");
define("CSS_CONTENT","../css/content_yahoo.css");

// Nome da classe que abstrai o banco de dados
define("DB_DEFAULT","../inc/db/mysql.class.php");

// Menu
define("MENU_EMPTY","Nenhum item disponível para este módulo"); 

// TextAreaField
define("TEXTAREA_RESTANTES","caracteres restantes");

// FileField
define("FILEFIELD_ARQUIVOATUAL","Arquivo atual:");
define("FILEFIELD_REMOVER","remover");

// DateField
define("DATEFIELD_IMAGEM","../img/icon-calen.gif");

// Filtro ativo em listas
define("FILTRO_ATIVO","Filtro ativo [" . "<a class='link' href='".$_SERVER['PHP_SELF']."?clear=1"."'>Limpar</a>]");

// Ícone de help
define("HELP_IMAGEM","../img/help.gif");
define("HELP_CORFUNDO","#FFFFDE");
define("HELP_CORTITULO","#006699");
define("HELP_CORTEXTO","#000000");
define("HELP_FONTTITULO","Tahoma, Verdana, Arial, Helvetica");
define("HELP_FONTTEXTO","Tahoma, Verdana, Arial, Helvetica");
define("HELP_TAMANHOTITULO","10pt");
define("HELP_TAMANHOTEXTO","8pt");

// Elementos das páginas
define("LISTA_ANTERIOR","&laquo; Anterior");
define("LISTA_PROXIMO","Próxima &raquo;");
define("LOGIN_MENSAGEM","Coloque aqui alguma informação sobre cadastro e permissão de usuários ou algum telefone para contato");

// expressões regulares
define("REGEX_EMAIL","^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$");
define("REGEX_CEP","^[0-9]{5}-{1}[0-9]{3}$");
define("REGEX_DATA","^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$");
define("REGEX_HORA","^(?:[01]\d|2[0-3]):[0-5]\d:[0-5]\d$");
define("REGEX_DECIMAL","^[+-]?((\d+|\d{1,3}(\,\d{3})+)(\.\d*)?|\.\d+)$");
define("REGEX_CPF","^[0-9]{3}.[0-9]{3}.[0-9]{3}-[0-9]{2}$");
?>