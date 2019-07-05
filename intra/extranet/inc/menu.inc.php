# arquivo de configura��o do menu
# par�metros separados por pipe (|)
# linhas que iniciam por "+" significam m�dulos e "-" significam itens do m�dulo
#   exemplo de m�dulo:
#   +|identifica��o do m�dulo|t�tulo|url|frame|n�vel acesso
#  
#   exemplo de item
#   -|identifica��o do m�dulo|t�tulo|url|frame|n�vel acesso
#
# para p�ginas p�blicas, n�vel de acesso zero

# defini��o dos m�dulos
+|mod_00|Home|../admin/evento_lista.php|content|1
+|mod_01|Usu�rio|../admin/usuario_lista.php?clear=1|content|1
+|mod_02|Se��es|../admin/evento_lista.php?clear=1|content|1

# itens do m�dulo 1
-|mod_01|Cadastro usu�rios|../admin/usuario_lista.php?clear=1|content|10
-|mod_01|Cadastro de n�veis|../admin/usuario_nivel_lista.php?clear=1|content|10
-|mod_01|Troca de senha|../admin/usuario_troca_senha_edicao.php?clear=1|content|1

# itens do m�dulo 2
#-|mod_02|Not�cias|../admin/noticia_lista.php?clear=1|content|1
#-|mod_02|Itens|../admin/link_lista.php?clear=1|content|1
#-|mod_02|Categorias|../admin/link_categoria_lista.php?clear=1|content|1
-|mod_02|Quadro de Avisos|../admin/evento_lista.php?clear=1|content|1

-|mod_02|Documentos|../admin/documento_lista.php?clear=1|content|1
-|mod_02|Categorias|../admin/documento_categoria_lista.php?clear=1|content|1


