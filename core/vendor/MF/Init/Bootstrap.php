<?php

namespace MF\Init;

abstract class Bootstrap{
    private $routes;

    abstract protected function initRoutes();

    public function __construct(){
        $this->initRoutes();
        $this->run($this->getUrl());
    }

    public function getRoutes(){
        return $this->routes;
    }

    public function setRoutes(array $routes){
        $this->routes = $routes;
    }

    protected function run($url){
        $rotaEncontrada = false;

        foreach ($this->getRoutes() as $key => $route){
            if($url == $route['route']){
                $rotaEncontrada = true;
                $class = "App\\Controllers\\".ucfirst($route['controller']);
                $controller = new $class;
                $action = $route['action'];
                $controller->$action();
                break;
            }
        }

        if (!$rotaEncontrada) {
            $this->render404();
        }
    }
    
    protected function getUrl(){
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    protected function render404() {
        header("HTTP/1.0 404 Not Found");    
        $class = "App\\Controllers\\IndexController";
        $controller = new $class;
        $controller->error404();
    }
}

?>