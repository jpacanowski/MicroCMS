<?php

/*
 * App Core Class
 * Creates URL & loads core controller
 * URL Format - /controller/method/params
 */

class Router {

    //protected $currentController = 'Posts';
    //protected $currentMethod = 'index';
    //protected $params = [];

    public function __construct() {

        $currentController = 'Posts';
        $currentMethod = 'index';
        $params = [];
        
        $url = $this->getUrl();

        // CONTROLLER

        if(file_exists('../app/controllers/' . ucfirst($url[0]) . '.php')) {
            $currentController = ucfirst($url[0]); unset($url[0]);
        }

        require_once '../app/controllers/' . $currentController . '.php';

        if(class_exists($currentController))
            $currentController = new $currentController();

        // METHOD

        if(isset($url[1])) {
            if(method_exists($currentController, $url[1])) {
                $currentMethod = $url[1]; unset($url[1]);
            }
        }

        // PARAMS

        $params = $url ? array_values($url) : [];

        call_user_func_array([$currentController, $currentMethod], $params);
    }

    public function getUrl() {

        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}