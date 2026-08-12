<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Servicio {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function obtenerTodos(): array {
        $sql = "SELECT * FROM servicios WHERE estado = 'activo' ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}