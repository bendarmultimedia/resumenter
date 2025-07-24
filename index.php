<?php

use Dotenv\Dotenv;
use Resumenter\Controller\ResumeController;
use Resumenter\FileLocator;
use Resumenter\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$loader = new FilesystemLoader(__DIR__ . '/templates');
$dotenv->load();
$routes = (new FileLocator())->getFileNames(__DIR__ . $_ENV['DATA_PATH']);
$routes[] = '';
$resumeCreator = new ResumeController((new Environment($loader)), $routes);
$router = new Router($resumeCreator, $routes, $_ENV['ROUTE_PREFIX']);

echo $router->handleRequest();