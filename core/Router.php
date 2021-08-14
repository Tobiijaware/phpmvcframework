<?php

namespace app\core;

class Router {
    protected array $routes = [];
    public Request $request;
    public Response $response;


    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;

    }

    public function get(string $path, $func){
        $this->routes['get'][$path] = $func;
    }

    public function post(string $path, $func){
        $this->routes['post'][$path] = $func;
    }

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        if($callback === false){
            $this->response->setStatusCode(404);
            return "Page Not Found";

        }
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){
            $callback[0] = new $callback[0]();
        }


        return call_user_func($callback, $this->request);

    }

    public function renderView($view, $params = [])
    {
        $layout = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}', $viewContent, $layout);
        include_once Application::$ROOT_DIR."/views/$view.php";
    }

    protected function layoutContent()
    {
        ob_start();
       include_once Application::$ROOT_DIR."/views/layouts/main.php";
       return ob_get_clean();
    }

    protected function renderOnlyView($view, $params){
        foreach($params as $key => $value){
            $$key = $value;
        }

        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }


}