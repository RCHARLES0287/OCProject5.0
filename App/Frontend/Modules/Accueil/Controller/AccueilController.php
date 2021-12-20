<?php


namespace App\Frontend\Modules\Accueil\Controller;


use Entity\PhotoEntity;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;

class AccueilController extends BackController
{
    public function executeAccueil(HTTPRequest $request)
        /**
         * @var $photoEntityInCarousel PhotoEntity
         */
    {
        $newPhotosManager = new PhotosManager();
        $photoEntitiesInCarousel [] = $newPhotosManager->getAllPhotosForCarousel();

        $newGaleriesManager = new GaleriesManager();

        $cheminsPhotosInCarousel = [];

        foreach ($photoEntitiesInCarousel as $photoEntityInCarousel)
        {
            $galerieId = $photoEntityInCarousel->galerie_id();
            $galerieEntity = $newGaleriesManager->getOneGalerie($galerieId);
            $galerieName = $galerieEntity->nom_galerie();
            $cheminPhoto = '/images/' . $galerieName . '/' . $photoEntityInCarousel->name();

            $cheminsPhotosInCarousel [] = $cheminPhoto;
        }


        $this->page->addVar('cheminsPhotosInCarousel', $cheminsPhotosInCarousel);
    }
}

