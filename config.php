<?php
    $dbHost = "Localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "uni_suporte";

    try {
        $conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);        
    } catch (mysqli_sql_exception $e) {
        echo "Erro ao tentar conectar ao banco de dados: " . $e->getMessage();
    }
?>