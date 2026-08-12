<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller {
    public function index() {
        $this->render('home/index', [
            'titulo_pagina' => 'Inicio - Helen Spa',
            'nav_activo'    => 'inicio'
        ]);
    }
}