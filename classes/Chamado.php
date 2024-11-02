<?php
require_once "DatabaseConnection.php";

class Chamado {
    private $titulo;
    private $categoria;
    private $descricao;
    private $conexao;

    public function __construct($titulo, $categoria, $descricao) {
        $this->titulo = $titulo;
        $this->categoria = $categoria;
        $this->descricao = $descricao;
        $this->conexao = (new DatabaseConnection())->getConexao();
    }

    public function criarChamado($id_usuario) {
        $query = "INSERT INTO chamados (titulo, categoria, Descricao, id_usuario) VALUES (?, ?, ?, ?)";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("sssi", $this->titulo, $this->categoria, $this->descricao, $id_usuario);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao adicionar chamado: " . $stmt->error);
        }
    }

    public static function consultarChamados($id_usuario) {
        $conexao = (new DatabaseConnection())->getConexao();
        $query = "SELECT * FROM chamados WHERE id_usuario = ?";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("i", $id_usuario);
        $stmt->execute();
        return $stmt->get_result();
    }
}
?>
