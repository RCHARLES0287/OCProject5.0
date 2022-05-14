<?php


namespace App\Frontend\Modules\Recherche\Controller;


use Entity\GalerieEntity;
use Entity\PhotoEntity;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;


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
            $matchingPhotos = $newPhotosManager->getAllPhotosFromTexteRecherche($texteRecherche);
//            $matchingPhotos = $newPhotosManager->getAllPhotos();

            $this->page->addVar('texte_recherche', $_GET["texte_recherche"]);
            $this->page->addVar('photos_trouvees', $matchingPhotos);

        }
    }


    /*public function executeResultatsrecherche(HTTPRequest $request)
    {

    }*/
}

