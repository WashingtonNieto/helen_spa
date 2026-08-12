<?php
namespace App\Controllers;

use Core\Controller; // <-- Agregar esta línea para importar el controlador base
use App\Models\Cita;

class CitaController extends Controller {
    private $citaModel;

    public function __construct() {
        $this->citaModel = new Cita();
    }

    /**
     * Muestra el formulario de agendamiento.
     */
    public function agendarView() {
        $servicios = $this->citaModel->obtenerServiciosActivos();

        $this->render('citas/agendar', [
            'titulo_pagina' => 'Agendar Cita - Helen Spa',
            'nav_activo'    => 'agendar',
            'servicios'     => $servicios,
            'errores'       => $_SESSION['errores'] ?? [],
            'datos_viejos'  => $_SESSION['old'] ?? []
        ]);

        unset($_SESSION['errores'], $_SESSION['old']);
    }

    /**
     * Consulta de citas por teléfono.
     */
    public function misCitas() {
        $buscado = isset($_GET['telefono']);
        $telefono = trim($_GET['telefono'] ?? '');
        $citas = [];

        if ($buscado && $telefono !== '') {
            $citas = $this->citaModel->buscarPorTelefono($telefono);
        }

        $this->render('citas/mis-citas', [
            'titulo_pagina' => 'Mis citas',
            'nav_activo'    => 'mis_citas',
            'buscado'       => $buscado,
            'telefono'      => $telefono,
            'citas'         => $citas
        ]);
    }

    /**
     * Guarda el agendamiento recibido por POST.
     */
    public function guardar() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . URL_BASE . '/agendar');
            exit;
        }

        $nombre      = trim($_POST['nombre'] ?? '');
        $telefono    = trim($_POST['telefono'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $servicio_id = filter_var($_POST['servicio_id'] ?? null, FILTER_VALIDATE_INT);
        $fecha       = trim($_POST['fecha'] ?? '');
        $hora        = trim($_POST['hora'] ?? '');

        $errores = [];
        if (empty($nombre)) $errores[] = 'El nombre es obligatorio.';
        if (empty($telefono)) $errores[] = 'El teléfono es obligatorio.';
        if (!$servicio_id) $errores[] = 'Debe seleccionar un servicio válido.';
        if (empty($fecha)) $errores[] = 'Seleccione una fecha para la cita.';
        if (empty($hora)) $errores[] = 'Seleccione la hora de atención.';

        if (!empty($errores)) {
            $_SESSION['errores'] = $errores;
            $_SESSION['old'] = $_POST;
            header('Location: ' . URL_BASE . '/agendar');
            exit;
        }

        $datosCita = [
            'nombre'      => $nombre,
            'telefono'    => $telefono,
            'email'       => $email,
            'servicio_id' => $servicio_id,
            'fecha'       => $fecha,
            'hora'        => $hora
        ];

        if ($this->citaModel->crear($datosCita)) {
            header('Location: ' . URL_BASE . '/mis-citas?telefono=' . urlencode($telefono));
            exit;
        } else {
            $_SESSION['errores'] = ['Error al guardar la cita. Intenta de nuevo.'];
            header('Location: ' . URL_BASE . '/agendar');
            exit;
        }
    }
}