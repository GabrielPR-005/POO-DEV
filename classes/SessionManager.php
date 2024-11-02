<?php
class SessionManager {
    public static function iniciarSessao() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setAutenticado($email) {
        $_SESSION["autenticado"] = "SIM";
        $_SESSION["email"] = $email;
    }

    public static function validarAcesso() {
        self::iniciarSessao();
        if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] != "SIM") {
            header("Location: index.php");
            exit();
        }
    }

    public static function encerrarSessao() {
        session_start();
        session_destroy();
        header("Location: index.php");
    }

    public static function getEmail() {
        return $_SESSION["email"];
    }
}
?>
