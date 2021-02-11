<?php


namespace App\Frontend\Modules\Photos\Controller;


use Entity\GalerieEntity;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;

class PhotosController extends \RCFramework\BackController
{
    public function executeShowallphotos(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();

        $allPhotosData = $photosManager->getAllPhotos();

        $this->page->addVar('photos', $allPhotosData);
    }


    public function executeShowonegalerie(HTTPRequest $request)
    {
        /*var_dump($request->postData('galerie_id'));
        exit;*/
        if (!Utilitaires::emptyMinusZero($request->postData('galerie_id')))
        {

            $photosManager = new PhotosManager();
            $galerieManager = new GaleriesManager();

            $galeriePhotosData = $photosManager->getOneGaleriePhotos($request->postData('galerie_id'));
            $galerieEntity = $galerieManager->getOneGalerie($request->postData('galerie_id'));

            $this->page->addVar('photos', $galeriePhotosData);
            $this->page->addVar('galerie_entity', $galerieEntity);

        }

    }


    public function executeShowOnePhoto(HTTPRequest $request)
    {

    }
}
