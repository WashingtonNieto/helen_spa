<?php
namespace App\Controllers;

use Controller;
use Auth;
use App\Models\Usuario;

class AuthController extends Controller {
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    /**
     * Muestra la vista de formulario de Login.
     */
    public function showLogin() {
        // Si ya está autenticado como admin, lo enviamos al panel
        if (Auth::isAdmin()) {
            header('Location: /helen_spa_php/admin/dashboard');
            exit;
        }

        $error = $_SESSION['error_login'] ?? null;
        unset($_SESSION['error_login']);

        $this->render('auth/login', [
            'titulo_pagina' => 'Iniciar Sesión - Helen Spa',
            'nav_activo'    => 'login',
            'error'         => $error
        ]);
    }

    /**
     * Procesa la solicitud POST del Login.
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /helen_spa_php/login');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $_SESSION['error_login'] = 'Por favor ingresa tu correo y contraseña.';
            header('Location: /helen_spa_php/login');
            exit;
        }

        $usuario = $this->usuarioModel->autenticar($email, $password);

        if ($usuario) {
            Auth::login($usuario);

            // Redireccionar según el rol
            if ($usuario['rol'] === 'admin') {
                header('Location: /helen_spa_php/admin/dashboard');
            } else {
                header('Location: /helen_spa_php/mis-citas');
            }
            exit;
        } else {
            $_SESSION['error_login'] = 'Correo o contraseña incorrectos.';
            header('Location: /helen_spa_php/login');
            exit;
        }
    }

    /**
     * Cierra la sesión del usuario.
     */
    public function logout() {
        Auth::logout();
        header('Location: /helen_spa_php/login?logout=1');
        exit;
    }
}