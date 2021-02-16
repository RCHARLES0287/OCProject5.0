<?php


namespace App\Frontend\Modules\Commande\Controller;


use Model\PhotosManager;
use RCFramework\HTTPRequest;

class CommandeController extends \RCFramework\BackController
{
    public function executeShowonearticle(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();
        $selectedPhoto = $photosManager->getOnePhoto($request->postData('photo_id'));

    }
}