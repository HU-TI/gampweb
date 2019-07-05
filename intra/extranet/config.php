<?php

# Configuracoes de banco de dados

//$con=mysql_connect ("186.202.152.244", "reequi_extrane", "reeq@2016") or die ('Não foi possivel conectar com o usuario: ' . mysql_error());
$con=mysql_connect ("127.0.0.1", "root", "") or die ('Não foi possivel conectar com o usuario: ' . mysql_error());
mysql_select_db ("reequi_extrane") or die("não foi possivel");
mysql_query("SET NAMES ‘utf8'");
mysql_query("SET character_set_connection=utf8");
mysql_query("SET character_set_client=utf8");
mysql_query("SET character_set_results=utf8");

?> 
