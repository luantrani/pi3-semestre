<?php
require_once __DIR__ . '/autoload.php';


$controllerName = $_GET['controller'] ?? 'Home';
$methodName     = $_GET['action']     ?? 'index';

$controllerClass = ucfirst($controllerName) . "Controller";

$arquivo = "controller/{$controllerClass}.php";

if (file_exists($arquivo)) {
    require_once $arquivo;
    
    if (class_exists($controllerClass)) {
        $objetoController = new $controllerClass();
        
        if (method_exists($objetoController, $methodName)) {
            $objetoController->$methodName();
        } else {
            die("Erro: A ação '$methodName' não existe.");
        }
    }
} else {
    die("Erro: O controlador '$controllerClass' não foi encontrado.");
}