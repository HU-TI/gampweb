<?
/* WEB - TOOLS - www.web-tools.kit.net [ Caso essa linha seja apagada
o sistema irá parar de funcionar] 
*/

# Configuracoes de banco de dados

$host = 'localhost'; // Host valor padrao é localhost
$usuariodb= 'root'; //Usuario de Conexao com  o MySQL
$senhadb= ''; // Senha de Conexao com o MySQL
$db= 'reequi_extranet'; //Banco de Dados MySQL

/*
$host ="186.202.152.244"; // Host valor padrao é localhost
$usuariodb="reequi_extrane"; //Usuario de Conexao com  o MySQL
$senhadb="reeq@2016"; // Senha de Conexao com o MySQL
$db="reequi_extrane"; //Banco de Dados MySQL
*/

# Tableas NAO ALTERE
$tb1="usuarios";
$tb2="controle";
$tb3="emails";


# Nao alterar nada abaixo
$con=mysql_connect ("$host", "$usuariodb", "$senhadb") or die ('Não foi possivel conectar com o usuario: ' . mysql_error());
mysql_select_db ("$db") or die("não foi possivel");

?> 
