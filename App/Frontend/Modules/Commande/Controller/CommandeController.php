<?php


namespace App\Frontend\Modules\Commande\Controller;


use Entity\GalerieEntity;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\PhotosManager;
use Model\TarifsManager;
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
        if (isset($_GET['photo_id'] && $_GET['galerie_id']) && is_string($_GET['photo_id'] && $_GET['galerie_id']))
        {
            $photosManager = new PhotosManager();
            $selectedPhoto = $photosManager->getOnePhoto($request->postData('photo_id'));
            $this->page->addVar('selected_photo', $selectedPhoto);

            $tarifsManager = new TarifsManager();
            $photoTarifs = $tarifsManager->getOnePhotoTarifs($_GET['photo_id']);

            $galerieManager = new GaleriesManager();
            $galerieEntity = $galerieManager->getOneGalerie($_GET['galerie_id']);

            $this->page->addVar('photoTarifs', $photoTarifs);
            $this->page->addVar('nom_galerie', $galerieEntity->nom_galerie());
        }
        else
        {
            throw new \Exception('Accès à l\'article demandé refusé. Paramètre(s) fourni(s) erroné(s)');
        }
    }
}
