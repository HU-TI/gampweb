# arquivo de configuração do menu
# parâmetros separados por pipe (|)
# linhas que iniciam por "+" significam módulos e "-" significam itens do módulo
#   exemplo de módulo:
#   +|identificação do módulo|título|url|frame|nível acesso
#  
#   exemplo de item
#   -|identificação do módulo|título|url|frame|nível acesso
#
# para páginas públicas, nível de acesso zero

# definição dos módulos
+|mod_00|Home|../admin/evento_lista.php|content|1
+|mod_01|Usuário|../admin/usuario_lista.php?clear=1|content|1
+|mod_02|Seções|../admin/evento_lista.php?clear=1|content|1

# itens do módulo 1
-|mod_01|Cadastro usuários|../admin/usuario_lista.php?clear=1|content|10
-|mod_01|Cadastro de níveis|../admin/usuario_nivel_lista.php?clear=1|content|10
-|mod_01|Troca de senha|../admin/usuario_troca_senha_edicao.php?clear=1|content|1

# itens do módulo 2
#-|mod_02|Notícias|../admin/noticia_lista.php?clear=1|content|1
#-|mod_02|Itens|../admin/link_lista.php?clear=1|content|1
#-|mod_02|Categorias|../admin/link_categoria_lista.php?clear=1|content|1
-|mod_02|Quadro de Avisos|../admin/evento_lista.php?clear=1|content|1

-|mod_02|Documentos|../admin/documento_lista.php?clear=1|content|1
-|mod_02|Categorias|../admin/documento_categoria_lista.php?clear=1|content|1


