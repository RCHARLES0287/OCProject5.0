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
        $newPhotosManager = new PhotosManager();

        if (!Utilitaires::emptyMinusZero($_GET["texte_recherche"]))
        {
            $texteRecherche = $_GET["texte_recherche"];
//            Pour supprimer les espaces en début et fin de chaine dans la requête de l'internaute
            $texteRecherche = trim($texteRecherche);
//            Pas besoin de gérer la casse ou les accents car déjà paramétré en MySQL dans PHPMyAdmin


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
                    $newPagePhotosHTML .= Utilitaires::remplacementMosaique($photo);
                }
                header('Content-Type: text/html; charset=utf-8');
                ob_clean();
                echo $newPagePhotosHTML;
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

        else if ($request->getExists('term'))
        {
            $newAutocompleteRecherchePhotos = $newPhotosManager->getSamplePhotosFromAutocompleteRecherche($request->dataGet('term'));

            $autocompleteResults = [];

            /**
             * @var PhotoEntity $photo
             */
            foreach ($newAutocompleteRecherchePhotos as $photo)
            {
                $autocompleteResults []= ["label"=>$photo->name(), "value"=>$photo->chemin_photo(), "id"=>$photo->id()];
            }
            $autocompleteResultsJson = json_encode($autocompleteResults, JSON_THROW_ON_ERROR);
            header('Content-Type: application/json; charset=utf-8');
            ob_clean();
            echo $autocompleteResultsJson;
            exit;
        }

        /*else
        {
            $this->page->addVar('photos_trouvees', []);
            $this->page->addVar('texte_recherche', '');
        }*/

    }


    /*public function executeResultatsrecherche(HTTPRequest $request)
    {

    }*/
}




