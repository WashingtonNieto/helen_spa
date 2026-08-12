<?php
namespace Core;

class Controller {
    public function render(string $view, array $data = []): void {
        extract($data);
        $viewFile = "../app/Views/{$view}.php";

        if (file_exists($viewFile)) {
            require_once "../app/Views/layouts/header.php";
            require_once $viewFile;
            require_once "../app/Views/layouts/footer.php";
        } else {
            die("Error: La vista '{$view}' no fue encontrada en {$viewFile}");
        }
    }
}