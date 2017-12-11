<?php

/*
 * Base Controller
 * Loads the models and views
 */
class Controller {

    // Load model
    public function model($model) {

        require_once '../app/models/' . $model . '.php';
        return new $model();
    }

    // Load view
    public function view($view, $data = []) {

        //if(file_exists('../app/views/' . $view . '.php')) {
        //    require_once '../app/views/' . $view . '.php';
        //} else {
        //    die('View does not exist');
        //}

        require_once '../app/Twig/Autoloader.php';

        Twig_Autoloader::register();
        $loader = new Twig_Loader_Filesystem('../app/views');
        $twig = new Twig_Environment($loader);

        $twig->registerUndefinedFunctionCallback(function($name) {
            if (function_exists($name)) {
                return new Twig_SimpleFunction($name, function() use($name) {
                    return call_user_func_array($name, func_get_args());
                });
                return false;
            }
        });

        echo $twig->render($view.'.html', $data);
    }
}