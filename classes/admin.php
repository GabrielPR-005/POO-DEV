<?php
require_once "Usuario.php";

class Admin extends Usuario {
    public function __construct($email) {
        parent::__construct($email);
    }

    public function gerenciarUsuarios() {
        
        $query = "SELECT * FROM usuarios";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function gerenciarCategorias() {
        
        $query = "SELECT * FROM categorias";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function gerenciarPrioridades() {
        
        $query = "SELECT * FROM prioridades";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function gerarRelatorios() {
        
        $query = "SELECT * FROM tickets";
        $stmt = $this->conexao->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}


?>