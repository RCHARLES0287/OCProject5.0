<?php


namespace RCFramework;


abstract class BackController extends ApplicationComponent
{
    protected $action = '';
    protected $module = '';
    protected $page = null;
    protected $view = '';

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->page = new Page($app);

        $this->setModule($module);
        $this->setAction($action);
        $this->setView($action);
    }

    public function execute()   // Lance la méthode du controller.
    {
        $method = 'execute' . ucfirst($this->action);

        if (!is_callable([$this, $method]))   // Ici, $this est AccueilController.
        {
            throw new \RuntimeException('L\'action "' . $this->action . '" n\'est pas définie sur ce module');
        }

        try
        {
            $this->$method($this->app->httpRequest());
//            Pour exécuter la fourniture des données nécessaires à l'affichage du layout global au chargement de chaque page
            if (is_callable([$this->app, 'executeLayoutContent']))
            {
                $this->app->executeLayoutContent($this->page);
            }
        } catch (\Throwable $e)
        {
            $this->page->addVar('exception', $e);
            header("content-type: text/plain; charset=utf-8");
            Utilitaires::logException($e);
            echo $e->getMessage();
            exit;
        }
    }

    public function page()
    {
        return $this->page;
    }

    public function setModule($module)
    {
        if (!is_string($module) || Utilitaires::emptyMinusZero($module))
        {
            throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
        }

        $this->module = $module;
    }

    public function setAction($action)
    {
        if (!is_string($action) || Utilitaires::emptyMinusZero($action))
        {
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }

    public function setView($view)
    {
        if (!is_string($view) || Utilitaires::emptyMinusZero($view))
        {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }

        $this->view = $view;

        $this->page->setContentFile( $this->app->name() . '/Modules/' . $this->module . '/Views/' . $this->view . '.html.twig');
    }

    public function getModule(): string
    {
        return $this->module;
    }
}