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
        if ($request->getExists('galerie_id'))
        {
            $photosManager = new PhotosManager();
            $galerieManager = new GaleriesManager();
            $galerieEntity = $galerieManager->getOneGalerie($request->dataGet('galerie_id'));

            if ($request->getExists('new_page_number'))
            {

                $newPagePhotos = $photosManager->getOneGaleriePhotosWithPageNumber($request->dataGet('galerie_id'), $request->dataGet('new_page_number'));

                $newPagePhotosHTML = '';
                foreach ($newPagePhotos as $photo)
                {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
                    $newPagePhotosHTML .= Utilitaires::remplacementMosaique(
                        '/images/'. $galerieEntity->nom_galerie() .'/'. $photo->serial_number(),
                        $photo->serial_number(),
                        $photo->serial_number() . ' : ' . $photo->lieu());
                }

                /*
                foreach ($newPagePhotos as $photo)
                {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
                    $newPagePhotosHTML .= '<div class="bloc_photo">
                                                <div class="photo_dans_galerie photo_dans_mosaique shadow-4-strong">
                                                    <img alt="description" src="/images/' . $galerieEntity->nom_galerie() . '/' . $photo->serial_number() .'">
                                                </div>
                                                <div class="descriptif_photo">' . $photo->serial_number() . ' : ' . $photo->lieu() . '</div>
                                           </div>';
                }
                */

/*
                {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
                    $newPagePhotosHTML .= '<div class="photo_dans_galerie photo_dans_mosaique shadow-4-strong">
                                                <a href="/images/' . $galerieEntity->nom_galerie() . '/' . $photo->serial_number() .'"
                                                   title="'. $photo->serial_number() .'">
                                                    <img alt="description"
                                                        src="/images/' . $galerieEntity->nom_galerie() . '/' . $photo->serial_number() .'">
                                                </a>
                                           </div>
                                           <div class="descriptif_photo">' . $photo->serial_number() . ' : ' . $photo->lieu() . '</div>';

                }
                */



                echo $newPagePhotosHTML;
                header('Content-Type: text/html; charset=utf-8');
                exit;
            }

            $numberOfPhotos = $photosManager->getOneGalerieNumberOfPhotos($request->dataGet('galerie_id'));
//            Ceil() si le résultat n'est pas un entier, renvoie l'arrondi à l'entier supérieur
            $numberOfPages = ceil($numberOfPhotos / (float)Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES);

            $galeriePhotosData = $photosManager->getOneGaleriePhotosWithPageNumber($request->dataGet('galerie_id'), 1);

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
