<?php


namespace App\Frontend;


use Model\GaleriesManager;

class FrontendApplication extends \RCFramework\Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
    }

    public function run() // Lance LE controller
    {
        $controller = $this->getController();
        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }

//    Pour fournir les données nécessaires à l'affichage du layout global
    public function executeLayoutContent($page)
    {
        $galeriesManager = new GaleriesManager();
        $allGaleries = $galeriesManager->getAllGaleries();

        $page->addVar('allGaleries', $allGaleries);
    }
}