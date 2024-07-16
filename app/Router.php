<?php

namespace app;

use controllers\auth\AuthController;
use controllers\home\HomeController;
use controllers\pages\PageController;
use controllers\roles\RoleController;
use controllers\users\UsersController;

class Router{
    // defining routes for regular expressions
    private $routes = [
        //determining the route using regular expressions
        '/^\/?$/' => ['controller' => 'home\\HomeController', 'action' => 'index'],
        '/^\/users(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'users\\UsersController'],
        '/^\/auth(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'auth\\AuthController'],
        '/^\/roles(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'roles\\RoleController'],
        '/^\/pages(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'pages\\PageController'],
        '/^\/(register|login|authentication|logout)(\/(?P<action>[a-zA-Z]+))?$/' => ['controller' => 'users\\AuthController'],
        '/^\/todo\/category(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'todo\category\\CategoryController'],
        '/^\/todo\/tasks(\/(?P<action>[a-zA-Z]+)(\/(?P<id>\d+))?)?$/' => ['controller' => 'todo\tasks\\TaskController'],
        '/^\/todo\/tasks\/by-tag(\/(?P<id>\d+))?$/' => ['controller' => 'todo\tasks\\TaskController', 'action'=> 'tasksByTag'],
        '/^\/todo\/tasks\/update-status(\/(?P<id>\d+))?$/' => ['controller' => 'todo\tasks\\TaskController', 'action'=> 'updateStatus'],

    ];

    public function run() {
       $uri = $_SERVER['REQUEST_URI'];
       $controller = null;
       $action = null;
       $params = null;

       //cycling through the routes while found that need:
       foreach ($this->routes as $pattern => $route) {
           //look at the route which corresponds  URI
           if(preg_match($pattern, $uri, $matches)){
               //get controller`s name from $route
               $controller = "controllers\\"   . $route['controller'] ;
               //get movie from $route or URI
               $action = $route['action'] ?? $matches['action'] ?? 'index';
               //get params
               $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
               //if found route that interrupt the cycle
               break;
           }
       }
       if(!$controller) {
           http_response_code(404);
           echo "Page not found!";
           return;
       }
       $controllerInstance = new $controller();
       if(!method_exists($controllerInstance, $action)) {
           http_response_code(404);
           echo "Page not found!";
           return;
       }
       call_user_func_array([$controllerInstance, $action], [$params]);
    }
}