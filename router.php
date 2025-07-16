<?php

$routes = [];

function add($path, $controller, $method) {
    global $routes;

    $routes[$path] = function() use ($controller, $method) {
        $controllerFile = "app/controllers/{$controller}.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;

            if (class_exists($controller)) {
                $instance = new $controller();

                if (method_exists($instance, $method)) {
                    $instance->$method();
                } else {
                    http_response_code(500);
                    echo "Método '$method' não encontrado na classe '$controller'.";
                }
            } else {
                http_response_code(500);
                echo "Classe '$controller' não encontrada.";
            }
        } else {
            http_response_code(500);
            echo "Arquivo do controller '$controllerFile' não encontrado.";
        }
    };
}

function route($uri) {
    global $routes;

    if (array_key_exists($uri, $routes)) {
        $routes[$uri]();
    } else {
        http_response_code(404);
        echo "Erro 404 - Página não encontrada.";
    }
}
