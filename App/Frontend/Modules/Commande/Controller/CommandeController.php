<?php


namespace App\Frontend\Modules\Commande\Controller;


use Entity\GalerieEntity;
use Entity\Ligne_de_commandeEntity;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\LignesDeCommandesManager;
use Model\PhotosManager;
use Model\TarifsManager;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;

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
        if ($request->getExists('photo_id') && $request->getExists('galerie_id'))
        {
//            Pour obtenir les infos de la photo
            $photosManager = new PhotosManager();
            $selectedPhoto = $photosManager->getOnePhoto($request->getData('photo_id'));
            $this->page->addVar('selected_photo', $selectedPhoto);

//            Pour obtenir tous les tarifs existants pour la photo
            $tarifsManager = new TarifsManager();
            $photoTarifs = $tarifsManager->getOnePhotoTarifs($request->getData('photo_id'));

            /*var_dump($photoTarifsRaw);
            exit();*/

            $dimensionsManager = new DimensionsManager();
            $allDimensions = $dimensionsManager->getAllDimensions();
            $this->page->addVar('allDimensions', $allDimensions);

//            Pour obtenir les infos de la galerie à laquelle est associée la photo
            $galerieManager = new GaleriesManager();
            $galerieEntity = $galerieManager->getOneGalerie($request->getData('galerie_id'));

            $this->page->addVar('photoTarifs', $photoTarifs);
            $this->page->addVar('galerieEntity', $galerieEntity);

            /*var_dump($photoTarifs);
            exit;*/
        }
        else
        {
            throw new \Exception('Accès à l\'article demandé refusé. Paramètre(s) fourni(s) erroné(s)');
        }
    }


    public function executeValidateonearticle (HTTPRequest $request)
    {
        if (isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles'])) {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];


                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];

//        var_dump($_SESSION['panier']);

                /*
                $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();
                $newLigneDeCommande = new LignesDeCommandesManager();
                $newLigneDeCommande->saveOneLigneDeCommande();
                */
                echo json_encode(['status'=>'Succès']);
            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur']);
            }

        }
        else
        {
            echo json_encode(['status'=>'Erreur']);
        }

        header('Content-Type: application/json');
        exit;
    }


    public function executeAffichagepanier (HTTPRequest $request)
    {
        /*
        var_dump('On est entré dans le panier');
        var_dump($_SESSION['panier']);
        exit;
        */
        $orderedArticles = [];

        foreach ($_SESSION['panier'] as $oneArticle)
        {
            /*
            var_dump($oneArticle);
            exit;
            */

            $newPhotoManager = new PhotosManager();
            $orderedArticle = $newPhotoManager->getOnePhoto($oneArticle["articleId"]);

            $newDimensionsManager = new DimensionsManager();
            $selectedDimensions = $newDimensionsManager->getOneEntryOfDimensions($oneArticle["dimensionsId"]);

            $numberOfArticles = $oneArticle["nombreArticles"];

            $newTarifsManager = new TarifsManager();
            $tarifsEntity = $newTarifsManager->getOnePhotoAndDimensionsTarif($oneArticle["articleId"], $oneArticle["dimensionsId"]);
            $photoTarif = $tarifsEntity->prix();


            $orderedArticles [] = [
                'orderedArticle' => $orderedArticle,
                'selectedDimensions' => $selectedDimensions,
                'numberOfArticles' => $numberOfArticles,
                'photoTarif' => $photoTarif
            ];
        }

        $this->page->addVar('orderedArticles', $orderedArticles);

        /*
        var_dump($orderedArticles);
        exit;
        */

    }
}
/*
$articleId = $_POST['idPhoto'];
$dimensionsId = $_POST['idDimensions'];
$nombreArticles = $_POST['nombreArticles'];

$_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];
*/

/*
array(3) {
    [0]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "1" ["nombreArticles"]=> string(1) "2"
    }
    [1]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "2" ["nombreArticles"]=> string(1) "5"
    }
    [2]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "2" ["nombreArticles"]=> string(1) "3"
    }
}*/
