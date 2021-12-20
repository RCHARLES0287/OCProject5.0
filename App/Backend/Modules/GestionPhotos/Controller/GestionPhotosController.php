<?php

namespace App\Backend\Modules\GestionPhotos\Controller;

use Entity\PhotoEntity;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;

class GestionPhotosController extends BackController
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
        /*var_dump('entrée dans controller de ordrecarousel');
        exit;*/


        $newPhotosManager = new PhotosManager();
        $allPhotos = $newPhotosManager->getAllPhotos();

        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $this->page->addVar('allPhotos', $allPhotos);
        $this->page->addVar('allGaleries', $allGaleries);

//        var_dump($allPhotos);
//        var_dump($allGaleries);
//        exit;


    }

    public function executeValidateordrecarousel(HTTPRequest $request)
    {
        for ($zoneCarousel=1; $zoneCarousel<6; $zoneCarousel++)
        {
            if (isset($_POST['input_' . $zoneCarousel]))
            {
                $carouselZoneiIdPhoto = $_POST['input_' . $zoneCarousel];
                $newPhotoManager = new PhotosManager();
                $newPhotoManager->updateOrdreCarousel($carouselZoneiIdPhoto, $zoneCarousel);
            }
        }

        header('Location: /admin/accueiladmin');
    }
}