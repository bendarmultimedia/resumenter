<?php

NameSpace Resumenter;

use JetBrains\PhpStorm\NoReturn;
use Resumenter\Controller\ControllerInterface;

final class Router
{
    public const PAGE_PARAM = 'job';

    /**
     * @param ControllerInterface $controller
     * @param array $routes
     * @param string|null $routePrefix
     */
    public function __construct(public ControllerInterface $controller, public array $routes = [], public ?string $routePrefix = '')
    {
    }


    /**
     * @return void
     */
    public function handleRequest(): void
    {
        $requestedUri = $_SERVER['REQUEST_URI'];
        $path = $this->path($requestedUri);

        if (!isset($_GET[self::PAGE_PARAM])) {
            if (in_array($path,  $this->routes)) {
                $this->renderPage($path);
            } else {
                $this->render404();
            }
            return;
        }

        $page = $_GET[self::PAGE_PARAM] ?? '';
        if (!in_array($page, $this->routes)) {
            $this->render404();
            return;
        }

        $this->redirectToPath($page);
    }

    /**
     * @param string $page
     * @return void
     */
    #[NoReturn]
    private function redirectToPath(string $page): void
    {
        $slash = $page === '' ? '' : '/';
        $queryUrl = $this->routePrefix.'/'. rtrim($page,"/").$slash;
        header("Location: $queryUrl", true, 302);
        exit;
    }

    /**
     * @param string $page
     * @return void
     */
    private function renderPage(string $page): void
    {
        $this->controller->render($page, []);
    }

    /**
     * @return void
     */
    private function render404(): void
    {
        http_response_code(404);
        echo "<h1>404 Not Found</h1><p>The page you requested does not exist.</p>";
    }

    /**
     * @param string $requestedUri
     * @return string
     */
    private function path(string $requestedUri): string
    {
        $path = parse_url($requestedUri, PHP_URL_PATH);
        $count = 1;
        $path = str_replace($this->routePrefix, '', $path, $count);
        return trim($path, '/');
    }
}