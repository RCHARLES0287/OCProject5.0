<?php


namespace App\Frontend\Modules\Commande\Controller;


use Entity\GalerieEntity;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\HTTPRequest;

class CommandeController extends \RCFramework\BackController
{
    public function executeShowallavailablephotos(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();
        $this->page->addVar('all_available_photos', $photosManager->getAllAvailablePhotos());

        $galeriesManager = new GaleriesManager();
        /* Version alternative

        $galeries = [];
        foreach ($availablePhotos as $photo)
        {
            if (!isset($galeries [$photo->galerie_id()]))
            {
                $galeries [$photo->galerie_id()] = $galeriesManager->getOneGalerie($photo->galerie_id());
            }

        }*/
        $this->page->addVar('galeries', $galeriesManager->getAllGaleries());
    }


    public function executeShowonearticle(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();
        $selectedPhoto = $photosManager->getOnePhoto($request->postData('photo_id'));
        $this->page->addVar('selected_photo', $selectedPhoto);

        $dimensionsManager = new DimensionsManager();
//        $availableDimensions = $dimensionsManager->
    }
}
