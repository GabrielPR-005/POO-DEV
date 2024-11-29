<?php
class SessionManager {
    public static function iniciarSessao() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setAutenticado($email, $tipoUsuario) {
        $_SESSION["autenticado"] = "SIM";
        $_SESSION["email"] = $email;
        $_SESSION["tipo_usuario"] = $tipoUsuario;
    }

    public static function validarAcesso($allowedRoles = []) {
        self::iniciarSessao();
        if (!isset($_SESSION["autenticado"]) || $_SESSION["autenticado"] != "SIM") {
            header("Location: index.php");
            exit();
        }
        if (!empty($allowedRoles)) {
            if (!isset($_SESSION["tipo_usuario"]) || !in_array($_SESSION["tipo_usuario"], $allowedRoles)) {
                
                header("Location: acesso_negado.php");
                exit();
            }
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

    public static function getTipoUsuario() {
        return $_SESSION["tipo_usuario"];
    }
}
?>
