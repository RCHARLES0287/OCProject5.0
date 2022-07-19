<?php


namespace RCFramework;


class Router
{
    /**
     * @var Route[] La liste des routes.
     */
    protected $routes = [];

    const NO_ROUTE = 1;

    public function addRoute(Route $route)
    {
        if (!in_array($route, $this->routes))
        {
            $this->routes[] = $route;
        }
    }

    public function getRoute($url)
    {

        foreach ($this->routes as $route)
        {
            // Si la route correspond à l'URL
            if ($route->match($url) !== false)
            {
                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL "' .$url.'"', self::NO_ROUTE);
    }
}