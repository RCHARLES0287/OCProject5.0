<?php


namespace App\Frontend\Modules\Photos\Controller;


use Model\PhotosManager;
use RCFramework\HTTPRequest;

class PhotosController extends \RCFramework\BackController
{
    public function executeShowallphotos(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();

        $allPhotosData = $photosManager->getAllPhotos();

        $this->page->addVar('photos', $allPhotosData);
    }


    public function executeShowOnePhoto(HTTPRequest $request)
    {

    }
}