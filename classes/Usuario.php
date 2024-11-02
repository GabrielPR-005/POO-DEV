<?php
require_once "DatabaseConnection.php";

class Usuario {
    private $email;
    private $conexao;

    public function __construct($email) {
        $this->email = $email;
        $this->conexao = (new DatabaseConnection())->getConexao();
    }

    public function getId() {
        $query = "SELECT id FROM usuarios WHERE email = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            return $user['id'];
        }
        throw new Exception("Usuário não encontrado.");
    }

    public function autenticar($senha) {
        $query = "SELECT * FROM usuarios WHERE email = ? AND senha = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("ss", $this->email, $senha);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
?>
