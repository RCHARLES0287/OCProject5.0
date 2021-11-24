<?php

namespace App\Backend\Modules\GestionPhotos\Controller;

use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\HTTPRequest;

class GestionPhotosController extends \RCFramework\BackController
{
    public function executeAddonephoto(HTTPRequest $request)
    {
        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $this->page->addVar('allGaleries', $allGaleries);
    }


    public function executeAjoutphotoform(HTTPRequest $request)
    {

    }

    public function executeOrdrecarousel(HTTPRequest $request)
    {
        var_dump('entrée dans controller de ordrecarousel');
        exit;


        $newPhotosManager = new PhotosManager();
        $allPhotos = $newPhotosManager->getAllPhotos();

        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $this->page->addVar('allPhotos', $allPhotos);
        $this->page->addVar('allGaleries', $allGaleries);

    }
}