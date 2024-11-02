<?php
class DatabaseConnection {
    private $conexao;

    public function __construct() {
        $dbHost = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "uni_suporte";

        $this->conexao = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);
        
        if ($this->conexao->connect_error) {
            throw new Exception("Erro ao conectar ao banco de dados: " . $this->conexao->connect_error);
        }
    }

    public function getConexao() {
        return $this->conexao;
    }
}
?>
