<?php

declare(strict_types=1);

namespace App;

use App\Action\CreateProductAction;
use App\Action\DeleteProductAction;
use App\Action\GetProductAction;
use App\Action\ListProductsAction;
use App\Action\UpdateProductAction;

final class Router
{
    private array $routes = [];

    public function addRoute(string $method, string $pattern, callable $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => $pattern,
            'handler' => $handler,
        ];
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH);
        $path = rtrim($path, '/') ?: '/';

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = $route['pattern'];
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches);
                $params = array_map('intval', $matches);
                ($route['handler'])(...$params);
                return;
            }
        }

        \App\Responder\JsonResponse::error('No existe ese endpoint', 404);
    }

    public static function create(): self
    {
        $router = new self();

        $router->addRoute('GET', '#^/productos$#', fn() => (new ListProductsAction())());
        $router->addRoute('GET', '#^/productos/(\d+)$#', fn(int $id) => (new GetProductAction())($id));
        $router->addRoute('POST', '#^/productos$#', fn() => (new CreateProductAction())());
        $router->addRoute('PUT', '#^/productos/(\d+)$#', fn(int $id) => (new UpdateProductAction())($id));
        $router->addRoute('DELETE', '#^/productos/(\d+)$#', fn(int $id) => (new DeleteProductAction())($id));

        return $router;
    }
}
