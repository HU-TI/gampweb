<?php

// Show all information, defaults to INFO_ALL
phpinfo();

// Show just the module information.
// phpinfo(8) yields identical results.
phpinfo(INFO_MODULES);

/*
try{
    // Faz conexão com banco de daddos
    $host = ('localhost');
    $user = ('root');
    $pass = ('');
    $bancodb = ('intra_gamp');

    $conecta = new PDO("mysql:host=$host;dbname=$bancodb", $user, $pass);
}catch(PDOException $e){
    // Caso ocorra algum erro na conexão com o banco, exibe a mensagem
    echo 'Falha ao conectar no banco de dados: '.$e->getMessage();
    die;
}

 // Define and perform the SQL SELECT query
        $sql = "SELECT * FROM `ramais``";
        $resultado = $conecta->query($sql);

        // Vamos imprimir os nossos resultados
        while($row = $resultado->fetch(PDO::FETCH_ASSOC)) {
            echo $row['id']. ' - '. $row['ramal'] . ' - ' . $row['location_id'] . '
';
        }
*/
        // Desconecta
       // $conecta = null;
    
    /*catch(PDOException $e) {
        // imprimimos a nossa excecao
        echo $e->getMessage();
    }*/


	
?> 
