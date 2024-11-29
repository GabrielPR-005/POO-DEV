<?php
require_once "Usuario.php";

class Cliente extends Usuario {
    public function __construct($email) {
        parent::__construct($email);
    }

    public function criarTicket($titulo, $descricao) {
        
        $query = "INSERT INTO tickets (titulo, descricao, cliente_id) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        $clienteId = $this->getId();
        $stmt->bind_param("ssi", $titulo, $descricao, $clienteId);
        $stmt->execute();
    }

    public function visualizarTickets() {
        
        $query = "SELECT * FROM tickets WHERE cliente_id = ?";
        $stmt = $this->conexao->prepare($query);
        $clienteId = $this->getId();
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function adicionarComentario($ticketId, $comentario) {
        
        $query = "INSERT INTO comentarios (ticket_id, comentario, autor_id) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        $clienteId = $this->getId();
        $stmt->bind_param("isi", $ticketId, $comentario, $clienteId);
        $stmt->execute();
    }
}

?>