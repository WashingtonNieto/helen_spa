<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Cita {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    /**
     * Obtiene los servicios disponibles para llenar el <select> del formulario.
     */
    public function obtenerServiciosActivos(): array {
        $sql = "SELECT id, nombre, precio, duracion_min FROM servicios ORDER BY nombre ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca las citas agendadas asociadas a un número telefónico.
     */
    public function buscarPorTelefono(string $telefono): array {
        $sql = "SELECT c.*, s.nombre AS servicio_nombre
                FROM citas c
                JOIN servicios s ON s.id = c.servicio_id
                WHERE c.telefono LIKE :telefono
                ORDER BY c.fecha, c.hora";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':telefono' => '%' . $telefono . '%']);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Inserta una nueva cita en la base de datos.
     */
    public function crear(array $datos): bool {
        $sql = "INSERT INTO citas (cliente_nombre, telefono, email, servicio_id, fecha, hora, estado, creado_en) 
                VALUES (:nombre, :telefono, :email, :servicio_id, :fecha, :hora, 'pendiente', NOW())";
        
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':nombre'      => $datos['nombre'],
            ':telefono'    => $datos['telefono'],
            ':email'       => $datos['email'],
            ':servicio_id' => $datos['servicio_id'],
            ':fecha'       => $datos['fecha'],
            ':hora'        => $datos['hora']
        ]);
    }
}