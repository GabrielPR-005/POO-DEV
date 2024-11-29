<?php
require_once "Usuario.php";

class Tecnico extends Usuario {
    public function __construct($email) {
        parent::__construct($email);
    }

    public function atribuirTicket($ticketId, $tecnicoId) {
        
        $query = "UPDATE tickets SET tecnico_id = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("ii", $tecnicoId, $ticketId);
        $stmt->execute();
    }

    public function atualizarTicket($ticketId, $status) {
        
        $query = "UPDATE tickets SET status = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("si", $status, $ticketId);
        $stmt->execute();
    }

    public function adicionarComentario($ticketId, $comentario) {
        
        $query = "INSERT INTO comentarios (ticket_id, comentario, autor_id) VALUES (?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        $tecnicoId = $this->getId();
        $stmt->bind_param("isi", $ticketId, $comentario, $tecnicoId);
        $stmt->execute();
    }
}


?>