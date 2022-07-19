<?php


namespace RCFramework;


abstract class Application
{
    protected $httpRequest;
    protected $httpResponse;
    protected $name;

    public function __construct()
    {
        $this->httpRequest = new HTTPRequest($this);
        $this->httpResponse = new HTTPResponse($this);

        $this->name = '';
    }

    public function getController()
    {
        $router = new Router;

        $xml = new \DOMDocument;    // Classe de PHP pour manipuler le DOM, manipuler des fichiers xml (lire, générer), et des dérivés (html, etc).
        $xml->load(__DIR__ . '/../../App/' . $this->name . '/Config/routes.xml');   // Lit le fichier xml des routes.

        $routes = $xml->getElementsByTagName('route');

        // On parcourt les routes du fichier XML.
        foreach ($routes as $route)
        {
            // On ajoute la route au routeur.
            $router->addRoute(new Route($route->getAttribute('url'), $route->getAttribute('module'), $route->getAttribute('action')));
        }

        try
        {
            // On récupère la route correspondante à l'URL.
            $matchedRoute = $router->getRoute($this->httpRequest->requestURI());

        } catch (\RuntimeException $e)
        {
            Utilitaires::logException($e);
            if ($e->getCode() == Router::NO_ROUTE)
            {
                // Si aucune route ne correspond, c'est que la page demandée n'existe pas.
                $this->httpResponse->redirect404($this);
            }
        }

        // On instancie le contrôleur.
        $controllerClass = 'App\\' . $this->name . '\\Modules\\' . $matchedRoute->module() . '\\Controller\\' . $matchedRoute->module() . 'Controller';
        return new $controllerClass($this, $matchedRoute->module(), $matchedRoute->action());   // Ici this fait référence à FrontendApplication.
    }

    abstract public function run();

    public function httpRequest()
    {
        return $this->httpRequest;
    }

    public function httpResponse()
    {
        return $this->httpResponse;
    }

    public function name()
    {
        return $this->name;
    }
}