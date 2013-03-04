<?php
class Route
{
    static function start()
    {
        $controllerName = 'Site';
        $actionName = 'index';
        $params = '';

        $routes = explode('/', $_SERVER['REQUEST_URI'], 4);

        $routeSolver = 0;
        if(empty($routes[1])) $routeSolver = 1;
        if(empty($routes[2])) $routeSolver += 2;

        switch ($routeSolver)
        {
            case 0:
                // Right way route
                $controllerName = $routes[1];
                $actionName = $routes[2];
                if(!empty($routes[3])) $params = $routes[3];
                break;
            case 2:
                // Controller is defined, action is missed
//                $controllerName = $routes[1];
                // controller set to default, action updated
                $actionName = $routes[1];
                break;
        }

        $modelName = ucfirst($controllerName);
        $controllerName = ucfirst($controllerName).'Controller';
        $actionName = ucfirst($actionName);

        // Load model's file
        $modelFile = $modelName.'.php';
        $modelPath = 'app/models/'.$modelFile;
        if(file_exists($modelPath)) include $modelPath;

        //Load controller's file
        $controllerFile = $controllerName.'.php';
        $controllerPath = 'app/controllers/'.$controllerFile;
        if(file_exists($controllerPath))
            include $controllerPath;
        else
        {
            Route::ErrorPage404();
        }

        $controller = new $controllerName;
        $action = $actionName;

        if(method_exists($controller, $action))
            $controller->$action($params);
        else
            Route::ErrorPage404();
    }


    static function redirect($page, $timeout = 0)
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('Refresh: '.$timeout.'; url='.$host.$page);
    }

    function ErrorPage404()
    {
        $host = 'http://'.$_SERVER['HTTP_HOST'].'/';
        header('HTTP/1.1 404 Not Found');
        header('Status: 404 Not Found');
        header('Location: '.$host.'error404');
    }
}