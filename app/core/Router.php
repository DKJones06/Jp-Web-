<?php

class Router {
    private $routes = [];

    public function get($path, $action) {
        $this->routes['GET'][$path] = $action;
    }

    public function post($path, $action) {
        $this->routes['POST'][$path] = $action;
    }

    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (isset($this->routes[$method][$path])) {
            return $this->runAction($this->routes[$method][$path]);
        }

        http_response_code(404);
        echo "404 - Ikke fundet";
    }

    private function runAction($action) {
        list($controller, $method) = explode('@', $action);

        $controllerFile = __DIR__ . '/../controllers/' . $controller . '.php';

        if (!file_exists($controllerFile)) {
            die("Controller ikke fundet: " . $controller);
        }

        require_once $controllerFile;

        $controllerObj = new $controller;
        return $controllerObj->$method();
    }
}
