<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "classes/SessionManager.php";
require_once "classes/Chamado.php";
require_once "classes/Usuario.php";

SessionManager::validarAcesso(['tecnico']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id_chamado = $_POST['id_chamado'];


        $chamado = new Chamado($id_chamado);


        $usuario = new Usuario(SessionManager::getEmail());
        $tecnicoId = $usuario->getId();


        $chamado->finalizarChamado($tecnicoId);

        header("Location: consultar_chamados_tecnico.php");
        exit();
    } catch (Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>
