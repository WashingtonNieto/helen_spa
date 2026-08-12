<?php

if (!function_exists('e')) {
    /**
     * Escapa caracteres especiales de HTML para prevenir ataques XSS.
     *
     * @param string|null $string Texto a limpiar.
     * @return string
     */
    function e(?string $string): string {
        return htmlspecialchars($string ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}

if (!function_exists('formatear_fecha_es')) {
    /**
     * Convierte una fecha en formato YYYY-MM-DD a un formato legible en español.
     * Ej: '2026-08-15' -> 'Sábado, 15 de Agosto de 2026'
     *
     * @param string|null $fecha Fecha en formato Y-m-d.
     * @return string
     */
    function formatear_fecha_es(?string $fecha): string {
        if (!$fecha || $fecha === '0000-00-00') {
            return 'Sin fecha';
        }

        $timestamp = strtotime($fecha);
        if (!$timestamp) {
            return $fecha;
        }

        $dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril', 
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto', 
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $diaSemana = $dias[date('w', $timestamp)];
        $diaNumero = date('j', $timestamp);
        $mesNumero = date('n', $timestamp);
        $anio      = date('Y', $timestamp);

        return "{$diaSemana}, {$diaNumero} de {$meses[$mesNumero]} de {$anio}";
    }
}

if (!function_exists('formatear_hora_es')) {
    /**
     * Convierte una hora de formato 24h (HH:MM:SS) a formato 12h con AM/PM.
     * Ej: '14:30:00' -> '02:30 PM'
     *
     * @param string|null $hora Hora en formato HH:MM o HH:MM:SS.
     * @return string
     */
    function formatear_hora_es(?string $hora): string {
        if (!$hora) {
            return '--:--';
        }

        $timestamp = strtotime($hora);
        if (!$timestamp) {
            return $hora;
        }

        return date('g:i A', $timestamp);
    }
}

if (!function_exists('formatear_moneda')) {
    /**
     * Formatea un número como valor monetario en pesos (COP).
     * Ej: 45000 -> '$ 45.000'
     *
     * @param float|int|null $monto
     * @return string
     */
    function formatear_moneda($monto): string {
        return '$ ' . number_format((float)($monto ?? 0), 0, ',', '.');
    }
}