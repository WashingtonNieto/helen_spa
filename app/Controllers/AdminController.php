<?php
namespace App\Controllers;

use Controller;
use Auth;
use App\Models\Cita;

class AdminController extends Controller {
    private $citaModel;

    public function __construct() {
        // Bloqueo de seguridad: Solamente administradores pueden acceder
        Auth::requireAdmin();
        $this->citaModel = new Cita();
    }

    /**
     * Renderiza el Panel Administrativo Principal.
     */
    public function dashboard() {
        $filtroEstado = $_GET['estado'] ?? null;
        
        $citas = $this->citaModel->obtenerTodasConServicio($filtroEstado);
        $stats = $this->citaModel->obtenerEstadisticas();

        $mensaje = $_SESSION['mensaje_admin'] ?? null;
        unset($_SESSION['mensaje_admin']);

        $this->render('admin/dashboard', [
            'titulo_pagina' => 'Panel de Administración - Helen Spa',
            'nav_activo'    => 'admin_dashboard',
            'citas'         => $citas,
            'stats'         => $stats,
            'filtro'        => $filtroEstado,
            'mensaje'       => $mensaje
        ]);
    }

    /**
     * Procesa el cambio de estado de una cita mediante POST.
     */
    public function cambiarEstadoCita() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = filter_var($_POST['cita_id'] ?? null, FILTER_VALIDATE_INT);
            $nuevoEstado = trim($_POST['estado'] ?? '');

            $estadosValidos = ['pendiente', 'confirmada', 'cancelada', 'completada'];

            if ($id && in_array($nuevoEstado, $estadosValidos, true)) {
                $this->citaModel->cambiarEstado($id, $nuevoEstado);
                $_SESSION['mensaje_admin'] = "El estado de la cita #{$id} fue actualizado a '{$nuevoEstado}'.";
            }
        }

        header('Location: /helen_spa_php/admin/dashboard');
        exit;
    }
}