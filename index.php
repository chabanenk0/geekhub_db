<?php

require_once 'vendor/autoload.php';

$controllerName = isset($_GET['controller']) ? $_GET['controller'] : 'students';
$controllerName = ucfirst($controllerName) . 'Controller';
$controllerName = 'Controllers\\' . $controllerName;

$controller = new $controllerName;

$actionName = isset($_GET['action']) ? $_GET['action'] : 'index';
$actionName = $actionName . 'Action';

$response = $controller->$actionName();

echo $response;