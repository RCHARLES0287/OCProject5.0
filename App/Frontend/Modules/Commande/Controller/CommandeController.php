<?php


namespace App\Frontend\Modules\Commande\Controller;


use Entity\CommandeEntity;
use Entity\GalerieEntity;
use Entity\Ligne_de_commandeEntity;
use Entity\PhotoEntity;
use Model\CommandesManager;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\LignesDeCommandesManager;
use Model\PhotosManager;
use Model\RangFactureCommandeManager;
use Model\TarifsManager;
use RCFramework\BackController;
use RCFramework\Entity;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;

class CommandeController extends BackController
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

                $isFound = false;
//                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi On peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId)
                    {
                        $lignePanier['nombreArticles'] += $nombreArticles;
                        $isFound = true;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }
                if ($isFound === false)
                {
                    $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];
                }


//        var_dump($_SESSION['panier']);

                /*
                $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();
                $newLigneDeCommande = new LignesDeCommandesManager();
                $newLigneDeCommande->saveOneLigneDeCommande();
                */
                echo json_encode(['status'=>'Succès', 'message'=>'Article ajouté au panier']);
            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur', 'message'=>'Echec de l\'ajout au panier']);
            }
        }
        else
        {
            Utilitaires::logMessage('Echec de l\'ajout au panier : paramètres incorrects');
            echo json_encode(['status'=>'Erreur', 'message'=>'Echec de l\'ajout au panier']);
        }

        header('Content-Type: application/json');
        exit;
    }


    public function executeAddorremoveonetoquantity (HTTPRequest $request)
    {
        if (isset($_POST['modifType']) && isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles']))
        {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];
                $modifType = $_POST['modifType'];

                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                $isFound = false;
                $newQuantity = 0;
                //                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi on peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId)
                    {
                        if ($modifType === 'add')
                        {
                            $lignePanier['nombreArticles'] += 1;
                        }
                        elseif ($modifType === 'remove' && $lignePanier['nombreArticles'] > 0)
                        {
                            $lignePanier['nombreArticles'] -= 1;
                        }
                        $newQuantity = $lignePanier['nombreArticles'];
                        $isFound = true;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }
//                Le cas où le panier ne contenait pas cet article dans ces dimensions
                if ($isFound === false)
                {
                    if ($modifType === 'add')
                    {
                        $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles + 1];
                        $newQuantity = $nombreArticles +1;
                    }
//                    Pas de else car on est dans le cas où la ligne photo-dimensions n'existe pas dans le panier (donc impossible de soustraire)
                }
                echo json_encode(['status'=>'Succès', 'newQuantity'=>$newQuantity]);
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


    public function executeValidationpanier (HTTPRequest $request)
    {
        if (!isset($_SESSION['utilisateur_entity']) || !$_SESSION['utilisateur_entity']->testEntityExists())
        {
//            $_SESSION['loggingin_redirection'] contient le chemin vers lequel devra être effectuée la redirection
            $_SESSION['loggingin_redirection'] = '/validationpanier';
            header('Location: /logginginform');
            exit;
        }
        else
        {
            $newRangFactureCommandeManager = new RangFactureCommandeManager();

            $newCommandeEntity = new CommandeEntity();
            $newCommandeEntity->setNumero_commande($newRangFactureCommandeManager->getAndUpdateCurrentNumeroFactureCommande('commande'));
            $newCommandeEntity->setMontant_total(0);
            $newCommandeEntity->setId_utilisateur($_SESSION['utilisateur_entity']->id());
            $newCommandeEntity->setNom_et_prenom_utilisateur($_SESSION['utilisateur_entity']->nom(), $_SESSION['utilisateur_entity']->prenom());
            $newCommandeEntity->setAdresse_utilisateur($_SESSION['utilisateur_entity']->numero_rue(),
                $_SESSION['utilisateur_entity']->nom_rue(),
                $_SESSION['utilisateur_entity']->code_postal(),
                $_SESSION['utilisateur_entity']->ville(),
                $_SESSION['utilisateur_entity']->pays());
            $newCommandeEntity->setValidation_panier(0);

            /*var_dump($newCommandeEntity->id());
            exit;*/

            $newCommandeManager = new CommandesManager();

            /*var_dump($newCommandeEntity->id());
            exit;*/
//            L'id est alimenté en automatique dans saveOneCommande
            $newCommandeManager->saveOneCommande($newCommandeEntity);


//            $newCommandeEntity->setId($newCommandeManager->getOneCommande());

            $prixTotal = 0;

            foreach ($_SESSION['panier'] as $lignePanier)
            {
                try
                {
                    $articleId = $lignePanier['articleId'];
                    $dimensionsId = $lignePanier['dimensionsId'];
                    $nombreArticles = $lignePanier['nombreArticles'];

                    $newPhotoManager = new PhotosManager();
                    $newPhotoEntity = $newPhotoManager->getOnePhoto($articleId);

                    $newTarifsManager = new TarifsManager();
                    $newTarifsEntity = $newTarifsManager->getOnePhotoAndDimensionsTarif($articleId, $dimensionsId);
                    $tarif = $newTarifsEntity->prix();

                    /*var_dump($tarif);
                    exit;*/

                    $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();

                    $prixLigneDeCommande = $nombreArticles * $tarif;
                    /*var_dump($prixLigneDeCommande);
                    exit;*/
                    $prixTotal = $prixTotal + $prixLigneDeCommande;


                    $newLigneDeCommandeEntity->setCommande_id($newCommandeEntity->id());
                    $newLigneDeCommandeEntity->setNom_prenom_adresse($_SESSION['utilisateur_entity']->nom(),
                        $_SESSION['utilisateur_entity']->prenom(),
                        $_SESSION['utilisateur_entity']->numero_rue(),
                        $_SESSION['utilisateur_entity']->nom_rue(),
                        $_SESSION['utilisateur_entity']->code_postal(),
                        $_SESSION['utilisateur_entity']->ville(),
                        $_SESSION['utilisateur_entity']->pays());
                    $newLigneDeCommandeEntity->setPhoto_serial_number($newPhotoEntity->serial_number());
                    $newLigneDeCommandeEntity->setPhoto_name($newPhotoEntity->name());
                    $newLigneDeCommandeEntity->setDimensions($dimensionsId);
                    $newLigneDeCommandeEntity->setTarif($tarif);
                    $newLigneDeCommandeEntity->setNombre_exemplaires($nombreArticles);

                    /*
                    var_dump($newLigneDeCommandeEntity);
                    exit;
                    */
                    $newLigneDeCommandeManager = new LignesDeCommandesManager();
                    $newLigneDeCommandeManager->saveOneLigneDeCommande($newLigneDeCommandeEntity);
                }
                catch (\Throwable $exception)
                {
                    Utilitaires::logException($exception);
                    Utilitaires::returnJsonAndExit(['status'=>'Erreur']);
                }
            }
            $newCommandeEntity->setMontant_total($prixTotal);
            $newCommandeManager->updateCommande($newCommandeEntity);
        }
    }



    /*public function executeRemoveonetoquantity (HTTPRequest $request)
    {
        if (isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles']))
        {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];

                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                //                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi On peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId && $lignePanier['nombreArticles'] > 0)
                    {
                        $lignePanier['nombreArticles'] -= 1;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }

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
    }*/




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

