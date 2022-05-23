<?php


namespace App\Frontend\Modules\Recherche\Controller;


use Entity\GalerieEntity;
use Entity\PhotoEntity;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;


class RechercheController extends BackController
{
    public function executeSendsearchrequest(HTTPRequest $request)
    {
        if (isset($_GET["texte_recherche"]))
        {
            $texteRecherche = $_GET["texte_recherche"];
//            Pour supprimer les espaces en début et fin de chaine dans la requête de l'internaute
            $texteRecherche = trim($texteRecherche);
//            Pas besoin de gérer la casse ou les accents car déjà paramétré en MySQL dans PHPMyAdmin

            $newPhotosManager = new PhotosManager();


            if ($request->getExists('new_page_number'))
            {
                $newPagePhotos = $newPhotosManager->getAllPhotosFromTexteRechercheWithPageNumber($texteRecherche, $request->dataGet('new_page_number'));

                $newPagePhotosHTML = '';

                /**
                 * @var PhotoEntity $photo
                 */
                foreach ($newPagePhotos as $photo)
                {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
                    $newPagePhotosHTML .= Utilitaires::remplacementMosaique(
                        '/images/'. $photo->chemin_photo(),
                        $photo->serial_number(),
                        $photo->serial_number() . ' : ' . $photo->lieu());
                }
                echo $newPagePhotosHTML;
                header('Content-Type: text/html; charset=utf-8');
                exit;
            }

            $matchingPhotos = $newPhotosManager->getAllPhotosFromTexteRechercheWithPageNumber($texteRecherche, 1);
//            $matchingPhotos = $newPhotosManager->getAllPhotos();
            $numberOfPhotos = $newPhotosManager->getNombrePhotosFromTexteRecherche($texteRecherche);
            $numberOfPages = ceil($numberOfPhotos / (float)Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES);


            $this->page->addVar('texte_recherche', $_GET["texte_recherche"]);
            $this->page->addVar('photos_trouvees', $matchingPhotos);
            $this->page->addVar('number_of_photos', $numberOfPhotos);
            $this->page->addVar('number_of_pages', $numberOfPages);
            $this->page->addVar('start_page', 1);
        }
    }


    /*public function executeResultatsrecherche(HTTPRequest $request)
    {

    }*/
}




