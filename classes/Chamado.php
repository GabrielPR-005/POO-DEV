<?php
require_once "DatabaseConnection.php";
require_once "Usuario.php";

class Chamado {
    private $id;
    private $titulo;
    private $descricao;
    private $categoria;
    private $status;
    private $id_usuario;
    private $tecnico_id;
    private $nivel;
    private $conexao;

    public function __construct($id = null) {
        $this->conexao = (new DatabaseConnection())->getConexao();
        if ($id) {
            $this->loadById($id);
        }
    }

    private function loadById($id) {
        $query = "SELECT * FROM chamados WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $chamadoData = $result->fetch_assoc();
            $this->id = $chamadoData['id'];
            $this->titulo = $chamadoData['titulo'];
            $this->descricao = $chamadoData['Descricao'];
            $this->categoria = $chamadoData['categoria'];
            $this->status = $chamadoData['status'];
            $this->id_usuario = $chamadoData['id_usuario'];
            $this->tecnico_id = $chamadoData['tecnico_id'];
            $this->nivel = $chamadoData['nivel'];
        } else {
            throw new Exception("Chamado não encontrado.");
        }
    }

    public function criarChamado($titulo, $categoria, $descricao, $id_usuario, $nivel = "n1") {
        $query = "INSERT INTO chamados (titulo, categoria, Descricao, id_usuario, nivel, status) 
                  VALUES (?, ?, ?, ?, ?, 'aberto')";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("sssis", $titulo, $categoria, $descricao, $id_usuario, $nivel);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao criar chamado: " . $stmt->error);
        }

        $this->id = $stmt->insert_id;
        $this->titulo = $titulo;
        $this->categoria = $categoria;
        $this->descricao = $descricao;
        $this->id_usuario = $id_usuario;
        $this->nivel = $nivel;
        $this->status = 'aberto';
    }

    public function adicionarComentario($comentario, $autor_id) {
        $usuario = new UsuarioById($autor_id);
        $tipo_usuario = $usuario->getTipo();

        if ($tipo_usuario === 'cliente' && $this->id_usuario != $autor_id) {
            throw new Exception("Você não tem permissão para comentar neste chamado.");
        } elseif ($tipo_usuario === 'tecnico' && $this->tecnico_id != $autor_id) {
            throw new Exception("Você não tem permissão para comentar neste chamado.");
        }

        $query = "INSERT INTO comentarios (id_chamado, comentario, id_usuario, data_comentario) 
                  VALUES (?, ?, ?, NOW())";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("isi", $this->id, $comentario, $autor_id);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao adicionar comentário: " . $stmt->error);
        }
    }

    public function finalizarChamado($tecnico_id) {
        if ($this->tecnico_id != $tecnico_id) {
            throw new Exception("Você não tem permissão para finalizar este chamado.");
        }
    
        $query = "UPDATE chamados SET status = 'finalizado', data_finalizacao = NOW() WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $this->id);
    
        if (!$stmt->execute()) {
            throw new Exception("Erro ao finalizar o chamado: " . $stmt->error);
        }
    
        $this->status = 'finalizado';
    }
    
    public function atribuirChamado($tecnico_id) {
        $query = "UPDATE chamados SET tecnico_id = ? WHERE id = ?";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("ii", $tecnico_id, $this->id);

        if (!$stmt->execute()) {
            throw new Exception("Erro ao atribuir o chamado: " . $stmt->error);
        }
        $this->tecnico_id = $tecnico_id;
    }

    public function consultarComentarios() {
        $query = "SELECT * FROM comentarios WHERE id_chamado = ? ORDER BY data_comentario DESC";
        $stmt = $this->conexao->prepare($query);
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result();
    }

    public function getId() {
        return $this->id;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getCategoria() {
        return $this->categoria;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getIdUsuario() {
        return $this->id_usuario;
    }

    public function getTecnicoId() {
        return $this->tecnico_id;
    }

    public function getNivel() {
        return $this->nivel;
    }

    public static function getChamadosDoUsuario($usuario_id) {
        $conexao = (new DatabaseConnection())->getConexao();
        $query = "SELECT * FROM chamados WHERE id_usuario = ? AND status != 'finalizado'";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("i", $usuario_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getChamadosDoTecnico($tecnico_id) {
        $conexao = (new DatabaseConnection())->getConexao();
        $query = "SELECT * FROM chamados WHERE tecnico_id = ? AND status != 'finalizado'";
        $stmt = $conexao->prepare($query);
        $stmt->bind_param("i", $tecnico_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public static function getChamadosNaoAtribuidos() {
        $conexao = (new DatabaseConnection())->getConexao();
        $query = "SELECT * FROM chamados WHERE tecnico_id IS NULL";
        $stmt = $conexao->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
}
?>
