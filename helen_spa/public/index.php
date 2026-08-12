<?php
// public/index.php

session_start();

// Definir la raíz absoluta del sitio web para enlaces y recursos estáticos
define('URL_BASE', '/helen_spa');

// Cargar autoloader y helpers
require_once __DIR__ . '/../core/Autoload.php';
\Core\Autoload::register();

require_once __DIR__ . '/../app/Helpers/functions.php';

use Core\Router;

$router = new Router();

// Definición de rutas (usando el método get)

$router->get('', 'HomeController@index');
$router->get('servicios', 'ServicioController@index'); // <-- Agregar esta línea
$router->get('mis-citas', 'CitaController@misCitas');
$router->get('agendar', 'CitaController@agendarView');

$router->dispatch();


// Despachar petición
// $router->dispatch($_SERVER['REQUEST_URI']);


// Despachar la petición
$router->dispatch();

// Iniciar enrutador...

// Autoload básico o inclusión directa
$router = new Router();


$router->get('', 'HomeController@index');
$router->get('mis-citas', 'CitaController@misCitas');
$router->get('agendar', 'CitaController@agendarView');

$router->dispatch();


// Rutas de agendamiento de citas
$router->get('agendar', 'CitaController@agendarView');
$router->post('agendar/guardar', 'CitaController@guardar');



// Rutas de Autenticación
$router->get('login', 'AuthController@showLogin');
$router->post('login/procesar', 'AuthController@login');
$router->get('logout', 'AuthController@logout');

// Rutas Administrativas Protegidas
$router->get('admin/dashboard', 'AdminController@dashboard');


// Acciones sobre citas
$router->post('admin/citas/estado', 'AdminController@cambiarEstadoCita');


