<?php

class Auth {
    /**
     * Inicia la sesión de un usuario de forma segura.
     */
    public static function login(array $usuario): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Previene ataques de fijación de sesión (Session Fixation)
        session_regenerate_id(true);

        $_SESSION['usuario_id']     = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_email']  = $usuario['email'];
        $_SESSION['usuario_rol']    = $usuario['rol'];
    }

    /**
     * Comprueba si hay un usuario autenticado.
     */
    public static function check(): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Verifica si el usuario autenticado es administrador.
     */
    public static function isAdmin(): bool {
        return self::check() && ($_SESSION['usuario_rol'] ?? '') === 'admin';
    }

    /**
     * Restringe el acceso únicamente a administradores.
     */
    public static function requireAdmin(): void {
        if (!self::isAdmin()) {
            $_SESSION['error_login'] = 'Debes iniciar sesión como administrador para acceder.';
            header('Location: /helen_spa_php/login');
            exit;
        }
    }

    /**
     * Cierra la sesión activa.
     */
    public static function logout(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION = [];
        session_destroy();
    }
}