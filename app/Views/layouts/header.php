<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo_pagina ?? 'Helen Spa - Centro de Bienestar') ?></title>
    
    <!-- Enlace al CSS usando la URL base -->
    <link rel="stylesheet" href="<?= URL_BASE ?>/public/css/estilos.css">
</head>
<body>

    <header class="header-main">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="<?= URL_BASE ?>/">
                    <h1>Helen <span>Spa</span></h1>
                </a>
            </div>

            <nav class="nav-principal">
                <ul>
                    <li><a href="<?= URL_BASE ?>/" class="<?= ($nav_activo ?? '') === 'inicio' ? 'activo' : '' ?>">Inicio</a></li>
                    <li><a href="<?= URL_BASE ?>/servicios" class="<?= ($nav_activo ?? '') === 'servicios' ? 'activo' : '' ?>">Servicios</a></li>
                    <li><a href="<?= URL_BASE ?>/agendar" class="<?= ($nav_activo ?? '') === 'agendar' ? 'activo' : '' ?>">Agendar Cita</a></li>
                    <li><a href="<?= URL_BASE ?>/mis-citas" class="<?= ($nav_activo ?? '') === 'mis_citas' ? 'activo' : '' ?>">Mis Citas</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">