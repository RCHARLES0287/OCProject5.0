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
        /*var_dump('On est bien dans le controller des photos');
        exit;*/
        /*var_dump($request->getData('galerie_id'));
        exit;*/

//        if ($request->getExists('galerie_id') && is_int($request->getData('galerie_id')))
        if ($request->getExists('galerie_id'))
        {
            $photosManager = new PhotosManager();
            $galerieManager = new GaleriesManager();
            $galerieEntity = $galerieManager->getOneGalerie($request->getData('galerie_id'));

//            if ($request->getExists('new_page_number') && is_int($request->getData('new_page_number')))
            if ($request->getExists('new_page_number'))
            {
                var_dump('On a cliqué sur un changement de page');
                exit;

                $newPagePhotos = $photosManager->getOneGaleriePhotosWithPageNumber($request->getData('galerie_id'), $request->getData('new_page_number'));

                $newPagePhotosHTML = '';
                foreach ($newPagePhotos as $photo)
                {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
                    $newPagePhotosHTML .= '<div class="descriptif_photo">' . $photo->serial_number() . ' : ' . $photo->lieu() . '</div>
                    <img alt="description" src="/images/' . $galerieEntity->nom_galerie() . '/' . $photo->serial_number() .'">';
                }

                echo $newPagePhotosHTML;
                header('Content-Type: text/html; charset=utf-8');
                exit;
            }

            $galeriePhotosData = $photosManager->getOneGaleriePhotos($request->getData('galerie_id'));
            $numberOfPhotos = count($galeriePhotosData);
//            Ceil() si le résultat n'est pas un entier, renvoie l'arrondi à l'entier supérieur
            $numberOfPages = ceil($numberOfPhotos / (float)Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES);

            $this->page->addVar('photos', $galeriePhotosData);
            $this->page->addVar('galerie_entity', $galerieEntity);
            $this->page->addVar('number_of_photos', $numberOfPhotos);
            $this->page->addVar('number_of_pages', $numberOfPages);
            $this->page->addVar('start_page', 1);
        }

    }


    public function executeShowonepagefromgalerie(HTTPRequest $request)
    {

    }

    public function executeShowOnePhoto(HTTPRequest $request)
    {

    }
}
