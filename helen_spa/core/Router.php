<?php
namespace Core;

class Router {
    private array $routes = [];

    public function get(string $path, string $handler): void {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void {
        $this->add('POST', $path, $handler);
    }

    private function add(string $method, string $path, string $handler): void {
        // Normalizar la ruta eliminando espacios y barras extremas
        $path = trim($path, '/');
        $this->routes[$method][$path] = $handler;
    }

    public function dispatch(): void {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        $rawUrl = $_GET['url'] ?? '';
        $path = trim($rawUrl, '/');

        if (isset($this->routes[$method][$path])) {
            $handler = $this->routes[$method][$path];
            [$controllerName, $action] = explode('@', $handler);

            $fullControllerClass = "App\\Controllers\\" . $controllerName;

            if (!class_exists($fullControllerClass)) {
                http_response_code(500);
                echo "<h1>Error 500</h1><p>No se encontró la clase del controlador: <strong>{$fullControllerClass}</strong></p>";
                return;
            }

            $controller = new $fullControllerClass();

            if (!method_exists($controller, $action)) {
                http_response_code(500);
                echo "<h1>Error 500</h1><p>El método <strong>{$action}</strong> no existe en el controlador <strong>{$fullControllerClass}</strong></p>";
                return;
            }

            $controller->$action();
            return;
        }

        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        echo "<p>Ruta buscada: '<strong>" . htmlspecialchars($path) . "</strong>' mediante método <strong>" . $method . "</strong></p>";
    }
}