<?php


namespace App\Frontend\Modules\Accueil\Controller;


use Model\GaleriesManager;
use RCFramework\HTTPRequest;

class AccueilController extends \RCFramework\BackController
{
    public function executeAccueil(HTTPRequest $request)
    {
        $galeriesManager = new GaleriesManager();
        $allGaleries = $galeriesManager->getAllGaleries();

        $this->page->addVar('allGaleries', $allGaleries);
    }
}