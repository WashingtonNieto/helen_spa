<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Usuario {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Busca un usuario activo por su correo electrónico.
     */
    public function obtenerPorEmail(string $email) {
        $sql = "SELECT id, nombre, email, password, rol, estado 
                FROM usuarios 
                WHERE email = :email AND estado = 'activo' 
                LIMIT 1";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Verifica si las credenciales de ingreso son correctas.
     */
    public function autenticar(string $email, string $password) {
        $usuario = $this->obtenerPorEmail($email);

        if ($usuario && password_verify($password, $usuario['password'])) {
            // Eliminar la contraseña del arreglo devuelto por seguridad
            unset($usuario['password']);
            return $usuario;
        }

        return false;
    }
}