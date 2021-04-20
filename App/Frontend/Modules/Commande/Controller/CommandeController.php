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
        if (isset($_POST['id_photo']) && isset($_POST['id_dimensions']) && isset($_POST['nombre_articles'])) {
            try
            {
                $articleId = $_POST['id_photo'];
                $dimensionsId = $_POST['id_dimensions'];
                $nombreArticles = $_POST['nombre_articles'];


                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];

//        var_dump($_SESSION['panier']);

//                echo json_encode(['status'=>'Succès']);
                $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();
                $newLigneDeCommande = new LignesDeCommandesManager();
                $newLigneDeCommande->saveOneLigneDeCommande();

            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur']);
            }

            header('Content-Type: application/json');


            exit;
        }
        else
        {
            var_dump('Erreur dans les données du $_POST');
        }

    }
}
