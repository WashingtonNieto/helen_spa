<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Servicio;

class ServicioController extends Controller {
    private $servicioModel;

    public function __construct() {
        $this->servicioModel = new Servicio();
    }

    public function index() {
        $servicios = $this->servicioModel->obtenerTodos();

        $this->render('servicios/index', [
            'titulo_pagina' => 'Servicios - Helen Spa',
            'nav_activo'    => 'servicios',
            'servicios'     => $servicios
        ]);
    }
}