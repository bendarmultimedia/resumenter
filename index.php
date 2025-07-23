<?php

use Dotenv\Dotenv;
use Resumenter\Controller\ResumeController;
use Resumenter\FileLocator;
use Resumenter\Router;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$fileLocator = new FileLocator();
$routes = $fileLocator->getFileNames(__DIR__ . $_ENV['DATA_PATH']);

$resumeCreator = new ResumeController($routes);
$router = new Router($resumeCreator, $routes, '/resumenter');

$router->handleRequest();

