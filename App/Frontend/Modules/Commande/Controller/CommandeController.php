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
        $availablePhotos = $photosManager->getAllAvailablePhotos();
        $this->page->addVar('all_available_photos', $availablePhotos);

        $galeriesManager = new GaleriesManager();
        $galeries = [];
        foreach ($availablePhotos as $photo)
        {
            $galeries = [$photo->galerie_id(), $galeriesManager->getOneGalerie($photo->galerie_id())];
        }
        $this->page->addVar('galeries', $galeries);
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
