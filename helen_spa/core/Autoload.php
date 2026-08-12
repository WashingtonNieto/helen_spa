<?php
namespace Core;

class Autoload {
    public static function register(): void {
        spl_autoload_register(function ($class) {
            // Mapeo de namespaces a directorios
            $prefixes = [
                'App\\'  => __DIR__ . '/../app/',
                'Core\\' => __DIR__ . '/../core/',
            ];

            foreach ($prefixes as $prefix => $base_dir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }

                $relative_class = substr($class, $len);
                $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });
    }
}