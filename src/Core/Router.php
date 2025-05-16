<?php
namespace News\Core;

class Router
{
    private array $routes = [];
    private array $errorHandlers = [];

    private $container;
    
    public function __construct(Container $container) {
        $this->container = $container;
    }

    /**
     * Új útvonal hozzáadása
     * @param string $path
     * @param callable|array $handler
     */
    public function add(string $path, $handler): void
    {
        $this->routes[$path] = [
            'path' => $this->normalizePath($path),
            'handler' => $handler
        ];
    }

    /**
     * 404 hibakezelő regisztrálása
     */
    public function addErrorHandler(int $code, callable $handler): void
    {
        $this->errorHandlers[$code] = $handler;
    }

    /**
     * Kérés feldolgozása
     */
    public function dispatch(string $url): string
    {
        $url = $this->normalizePath($url);
    
        foreach ($this->routes as $route) {
            $params = [];
            if ($this->matchPath($route['path'], $url, $params)) {
                return $this->invokeHandler($route['handler'], $params);
            }
        }
    
        return $this->dispatchError(404);
    }

    private function invokeHandler(array $handler, array $params = []): string
    {
        try {
            $className = $handler[0];
            $methodName = $handler[1];
    
            $controller = $this->container->resolve($className);
    
            if (!method_exists($controller, $methodName)) {
                throw new \Exception("Method not found: $methodName in $className");
            }
            $result = call_user_func_array([$controller, $methodName], $params);
            return is_string($result) ? $result : json_encode($result); // 👈 itt a változtatás
           // return call_user_func_array([$controller, $methodName], array_values($params)); //vagy sima params array value nélkül
        } catch (\Exception $e) {
            return $this->dispatchError(500, $e->getMessage());
        }
    }

    /**
     * Hibakezelés
     */
    private function dispatchError(int $code, string $message = ''): string
    {
        http_response_code($code);
    
        if (isset($this->errorHandlers[$code])) {
            ob_start();
            call_user_func($this->errorHandlers[$code], $message);
            return ob_get_clean();
        } else {
            return "HTTP $code Error: $message";
        }
    }
    

    /**
     * URL normalizálás
     */
    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        $path = filter_var($path, FILTER_SANITIZE_URL);
        return '/' . strtolower($path);
    }

    private function matchPath(string $routePath, string $requestPath, array &$params = []): bool {
        $routeParts = explode('/', trim($routePath, '/'));
        $requestParts = explode('/', trim($requestPath, '/'));
    
        if (count($routeParts) !== count($requestParts)) {
            return false;
        }
    
        foreach ($routeParts as $key => $part) {
            if (preg_match('/^{\w+}$/', $part)) {
                $paramName = trim($part, '{}');
                $params[$paramName] = $requestParts[$key];
            } elseif ($part !== $requestParts[$key]) {
                return false;
            }
        }
    
        return true;
    }
}