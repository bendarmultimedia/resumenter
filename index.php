<?php

use Dotenv\Dotenv;
use Resumenter\Controller\ResumeController;
use Resumenter\FileLocator;
use Resumenter\Router;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
$routes = (new FileLocator())->getFileNames(__DIR__ . $_ENV['DATA_PATH']);
$routes[] = '';
$resumeCreator = new ResumeController($routes);
$router = new Router($resumeCreator, $routes, $_ENV['ROUTE_PREFIX']);

$router->handleRequest();