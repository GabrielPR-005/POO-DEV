<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/SessionManager.php";
require_once "classes/Chamado.php";
require_once "classes/Usuario.php";

SessionManager::validarAcesso(['cliente', 'tecnico']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_chamado = $_POST['id_chamado'];
        $comentario = $_POST['comentario'];

        if (empty($id_chamado) || empty($comentario)) {
            throw new Exception("Os campos do comentário não podem estar vazios.");
        }

        $usuario = new Usuario(SessionManager::getEmail());
        $id_usuario = $usuario->getId();
        $tipo_usuario = $usuario->getTipo();

        $chamado = new Chamado($id_chamado);

        // Verifica se o usuário tem permissão para comentar
        if ($tipo_usuario === 'cliente' && $chamado->getIdUsuario() != $id_usuario) {
            throw new Exception("Você não tem permissão para comentar neste chamado.");
        } elseif ($tipo_usuario === 'tecnico' && $chamado->getTecnicoId() != $id_usuario) {
            throw new Exception("Você não tem permissão para comentar neste chamado.");
        }

        $chamado->adicionarComentario($comentario, $id_usuario);

        if ($tipo_usuario === 'cliente') {
            header("Location: consultar_chamado.php");
        } elseif ($tipo_usuario === 'tecnico') {
            header("Location: consultar_chamados_tecnico.php");
        }
        exit();
    } catch (Exception $e) {
        echo "Erro ao adicionar comentário: " . $e->getMessage();
    }
}
?>
