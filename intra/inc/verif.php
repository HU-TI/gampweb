<?php
//A gente pode levar o burro até a fonte, mas não pode obrigar ele a beber água
include ('../config/config.php');
$http = $_SERVER['SERVER_NAME'];

//Função somente para validar usuário e senha no AD
function valida_ldap($srv, $usr, $pwd){
    $ldap_server = $srv;
    $auth_user = $usr;
    $auth_pass = $pwd;
    // Tenta se conectar com o servidor
    if (!($connect = @ldap_connect($ldap_server))) {
        return FALSE;
    }
    // Tenta autenticar no servidor
    if (!($bind = @ldap_bind($connect, $auth_user, $auth_pass))) {
        // se não validar retorna false
        return FALSE;
    } else {
        // se validar retorna true
        return TRUE;
    }
}

// Uso dessa função
$login = $_REQUEST['usu'];
$server = "10.100.1.10"; //IP ou nome do servidor
$dominio = "@hmd.local"; //Dominio Ex: @gmail.com
$user = $login.$dominio;
$pass = $_REQUEST['senha'];

if (!($search=@ldap_search($connect, $base_dn, '(|(samaccountname='.$login.'))'))) {
//print "Unable to search ldap server";
}else{
    print $login;
}
//Confere se os campos de login e senha foram preenchidos
if(!$login || !$pass){
    ?> <script type="text/javascript">alert('Campos obrigatórios ficaram vazios, tente novamente...');</script><?php
    echo "<meta http-equiv='refresh' content='0.01; URL=http://$http/?tela=login' />";
    //print "Campos obrigatórios ficaram vazios, tente novamente... <br> Você está sendo redirecionado à página principal...";
    //echo "<br> <a href='http://$http/'>Voltar</a>";
}else{
    //Utiliza a função e segue a operação caso autentique o usuário
    if (valida_ldap($server, $user, $pass)) {
        echo "Usuário autenticado...<br>"; 
        //Confere se esse usuário já existe no banco   
        $sql = "SELECT * FROM `usuarios` WHERE `login` LIKE '$login'";
        $query = $mysqli->query( $sql );
        if( $query->num_rows > 0 ) {//se retornar algum resultado
            echo 'Já existe!';
            $resultado = mysqli_fetch_assoc($query);
            // Se a sessão não existir, inicia uma
            if (!isset($_SESSION)) session_start();
            // Salva os dados encontrados na sessão
            $_SESSION['UsuarioID'] = $resultado['id'];
            $_SESSION['UsuarioLogin'] = $resultado['login'];
            $_SESSION['UsuarioNome'] = $resultado['nome'];
            $_SESSION['UsuarioSetorId'] = $resultado['setor_id'];
            $_SESSION['UsuarioAcesso'] = $resultado['acesso'];
            //print $_SESSION['UsuarioLogin'];
            // Redireciona o visitante
            header("Location: http://$http/?tela=?"); exit;
            /*$sqlglpi = "SELECT * FROM `glpi_users` WHERE `name` LIKE '$login'";
            $queryglpi = $mysqliglpi->query( $sql );
            if( $queryglpi->num_rows > 0 ) {//se retornar algum resultado
            echo 'Conecta GLPI';
            }else{
                echo 'Não conecta GLPI';
            }*/
        } else {
            //Pegar informações de Nome e Setor no AD
            //Usuário e senha com acesso Admin no AD
            $adminUser = 'servweb';
            $adminPass = 'hu@esc@gamp@web';
            //Checa o login admin
            if(isset($adminUser)&& isset($adminPass)){
                $adServer = "ldap://10.100.1.10";

                $ldap = ldap_connect($adServer);
                
                $ldaprdn = 'hmd'."\\".$adminUser;

                ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
                ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

                $bind = @ldap_bind($ldap, $ldaprdn, $adminPass);
                
                if($bind){
                    $filter = "(sAMAccountName=$login)";
                    $result = ldap_search($ldap, "dc=hmd,dc=local", $filter);                  
                    $info = ldap_get_entries($ldap, $result);
                    for ($i=0; $i < $info["count"] ; $i++) {
                        
                        if ($info["count"] > 1)
                        break;
                        //Armazena nome e setor
                        $cn = $info[$i]['cn'][0];
                        $dept = $info[$i]['department'][0];
                        
                        $userDn = $info[$i]["distinguishedname"][0];
                    }
                    @ldap_close($ldap);
                }else{
                    $msg = "Invalid email address / password";
                    echo $msg;
                }
            }
            //Retorna o ID do setor a partir do setor do AD
            $deptSql = "SELECT `id` FROM `setores` WHERE `setor` LIKE '%$dept%'";
            $deptQuery = $mysqli->query( $deptSql );
            $deptResul = mysqli_fetch_assoc($deptQuery);
            $setor_id = $deptResul['id'];
            print $setor_id;
            //Insere dados na base de login da Intra
            $sql = "INSERT INTO `USUARIOS` (`login`,`nome`,`setor_id`,`acesso`)VALUES('$login','$cn', '0','1');"; 
            $query = $mysqli->query( $sql );
            $sql = "SELECT * FROM `usuarios` WHERE `login` LIKE '$login'";
            $query = $mysqli->query( $sql );
            if( $query->num_rows > 0 ) {//se retornar algum resultado
                echo 'Criado!';
                $resultado = mysqli_fetch_assoc($query);
                // Se a sessão não existir, inicia uma
                if (!isset($_SESSION)) session_start();
                // Salva os dados encontrados na sessão
                $_SESSION['UsuarioID'] = $resultado['id'];
                $_SESSION['UsuarioLogin'] = $resultado['login'];
                $_SESSION['UsuarioNome'] = $resultado['nome'];
                $_SESSION['UsuarioSetorId'] = $resultado['setor_id'];
                $_SESSION['UsuarioAcesso'] = $resultado['acesso'];
                //print $_SESSION['UsuarioLogin'];
                // Redireciona o visitante
                //header("Location: restrito.php"); exit;
                header("Location: http://$http/?tela=?"); exit;
            }
        }
    } else {
        ?> <script type="text/javascript">alert('Usuário ou senha inválida, verifique os dados... ');</script><?php
        echo "<meta http-equiv='refresh' content='0.01; URL=http://$http/?tela=login' />";
        //echo '<div id ="conteudo">Usuário ou senha inválida, verifique os dados... <br>Você está sendo redirecionado à pagina principal...';
        //echo "<br> <a href='http://$http/'>Voltar</a>";
    }
}
?>