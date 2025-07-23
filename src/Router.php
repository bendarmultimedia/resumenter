<?php

NameSpace Resumenter;

use Resumenter\Controller\ControllerInterface;

final class Router
{
    public const PAGE_PARAM = 'job';

    public function __construct(public ControllerInterface $controller, public array $routes = [], public ?string $routePrefix = '')
    {
    }


    public function handleRequest(): void
    {
        $requestedUri = $_SERVER['REQUEST_URI'];
        $path = $this->path($requestedUri);

        if (!isset($_GET[self::PAGE_PARAM]) && $path !== '') {
            if (in_array($path,  $this->routes)) {
                $this->renderPage($path);
                return;
            } else {
                $this->render404();
                return;
            }
        }

        $page = $_GET[self::PAGE_PARAM] ?? '';
        if (!in_array($page, $this->routes)) {
            $this->render404();
            return;
        }

        $this->redirectToPath($page);
    }
    private function redirectToPath(string $page): void
    {
        $queryUrl = $this->routePrefix.'/'. rtrim($page,"/").'/';
        header("Location: $queryUrl", true, 302);
        exit;
    }

    private function renderPage(string $page): void
    {
        $this->controller->render($page, []);
    }

    private function render404(): void
    {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>The page you requested does not exist.</p>";
    }

    private function path(string $requestedUri): string
    {
        $path = parse_url($requestedUri, PHP_URL_PATH);
        $count = 1;
        $path = str_replace($this->routePrefix, '', $path, $count);
        return trim($path, '/');
    }
}