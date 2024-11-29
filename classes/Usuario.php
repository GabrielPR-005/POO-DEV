<?php
require_once "DatabaseConnection.php";

class Usuario {
    protected $id;
    protected $email;
    protected $tipo;
    protected $senha;
    protected $conexao;

    public function __construct($email) {
        $this->email = $email;
        $this->conexao = (new DatabaseConnection())->getConexao();
        $this->loadByEmail();
    }

    protected function loadByEmail() {
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("s", $this->email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->id = $user['id'];
            $this->tipo = $user['tipo'];
            $this->senha = $user['senha'];
        } else {
            throw new Exception("Usuário não encontrado.");
        }
    }

    public function getTipo() {
        return $this->tipo;
    }    

    public function getId() {
        return $this->id;
    }

    public function autenticar($senha) {
        return $senha === $this->senha;
    }
}

class UsuarioById extends Usuario {
    public function __construct($id) {
        $this->id = $id;
        $this->conexao = (new DatabaseConnection())->getConexao();
        $this->loadById();
    }

    protected function loadById() {
        $query = "SELECT * FROM usuarios WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $this->email = $user['email'];
            $this->tipo = $user['tipo'];
            $this->senha = $user['senha'];
        } else {
            throw new Exception("Usuário não encontrado.");
        }
    }
}
?>
