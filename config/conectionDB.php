<?php

    $hostDB   = "localhost"; //Define o local do servidor de BD
    $userDB   = "root"; //Define o usuário do BD (Padrão: root)
    $passwordDB  = ""; //Define a senha do BD (Padrão: "" [Em branco])
    $database = "magraoMotos"; //Define com qual base será realizada a conexão

    //Função do PHP para estabelecer a conexão com o BD
    $conn     = mysqli_connect($hostDB, $userDB, $passwordDB, $database);

    //Verificar se há conexão com o BD
    if(!$conn){
        echo "<p>Erro ao tentar conectar a aplicação à base de dados <strong>$database</strong></p>";
    }

?>