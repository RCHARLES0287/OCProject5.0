<?php

namespace App\Backend\Modules\GestionGaleries\Controller;

use Entity\GalerieEntity;
use Entity\PhotoEntity;
use Exception;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\Application;
use RCFramework\BackController;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;
use Throwable;

class GestionGaleriesController extends BackController
{
    public function executeAjoutgalerie (HTTPRequest $request)
    {

    }

    public function executeAjoutgalerieform(HTTPRequest $request)
    {
        if (!isset($_POST['galerie_name'])
            || !isset($_FILES['choix_miniature'])
            || $_FILES['choix_miniature']['error'] != 0)
        {
            throw new Exception("Erreur dans le remplissage du formulaire d'ajout de galerie");
        }
        else
        {
            //            On vérifie que le fichier joint dans le formulaire ne dépasse pas environ 20Mo
            if ($_FILES['choix_photo']['size'] > 20000000)
            {
                throw new Exception("Le fichier joint dépasse la taille limite d'environ 20Mo");
            }
            else
            {
//                On s'assure que l'extension du fichier reçu est bien dans la liste des extensions qu'on souhaite authoriser
                $fileInfos = pathinfo($_FILES['choix_miniature']['name']);
                $extension = strtolower($fileInfos['extension']);

                if (!in_array($extension, PhotoEntity::ALLOWED_EXTENSIONS))
                {
                    throw new Exception("Echec : extension non supportée");
                }
                else
                {


                    $newGalerieEntity = new GalerieEntity();
                    $newGalerieEntity->setNom_galerie(ucfirst($_POST['galerie_name']));
                    $newGalerieEntity->setChemin_miniature($_FILES['choix_miniature']['name']);
                    $newGalerieEntity->setOrdre_affichage(null);

//                    $newGalerieEntity = $newGalerieManager->getOneGalerie($_POST['galerie_id']);
                    /*var_dump($newGalerieEntity);
                    exit;*/
                    $newGalerieDir = __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie();
                    $newGalerieMiniatureDir = $newGalerieDir . '/Miniature/';
                    $newGalerieMiniatureFile = $newGalerieMiniatureDir . $newGalerieEntity->chemin_miniature();

                    if (!is_dir($newGalerieMiniatureDir))
                    {
//                        True en troisième paramètre indique que la fonction sera récursive, donc elle crée les dossiers intermédiaires.
                        if (mkdir($newGalerieMiniatureDir, 0777, true) === false)
                        {
                            Utilitaires::logMessage("Echec de la création du dossier miniature de la galerie");
                            echo "Echec de la création du dossier miniature de la galerie";
                        }
                    }

                    if (!file_exists($newGalerieMiniatureFile))
                    {
                        $uploadPhoto = move_uploaded_file($_FILES['choix_miniature']['tmp_name'],
//                        __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie() . '/Miniature/' . $newGalerieEntity->chemin_miniature());
                            $newGalerieMiniatureFile);

                        if ($uploadPhoto != true)
                        {
                            throw new Exception("Echec de l'enregistrement de la miniature");
                        }
                        else
                        {
                            try
                            {
                                /*var_dump("C'est bon on est à la dernière étape");
                                exit;*/
                                $newGalerieManager = new GaleriesManager();
                                $newGalerieManager->saveGalerie($newGalerieEntity);
                            }
                            catch (Throwable $exception)
                            {
                                echo "Echec de l'enregistrement de la galerie dans la BDD";
                                Utilitaires::logException($exception);
                            }
                        }
                    }
                }
            }
        }
    }

    public function executeSuppressiongalerie (HTTPRequest $request)
    {
        $newGalerieManager = new GaleriesManager();
        $allGaleries = $newGalerieManager->getAllGaleries();

        $this->page->addVar('all_galeries', $allGaleries);
    }

    public function executeConfirmationsuppressiongaleries (HTTPRequest $request)
    {
        if (!$request->postExists('checkbox_suppr_galerie'))
        {
            throw new Exception("Vous n'avez sélectionné aucune galerie");
        }
        else
        {
//            var_dump($request->postData('checkbox_suppr_galerie'));
//            exit;
            $newGaleriesManager = new GaleriesManager();
            $newPhotosManager = new PhotosManager();

            $galeriesId = $request->postData('checkbox_suppr_galerie');
            foreach ($galeriesId as $galerieId)
            {
                $newGalerieEntity = $newGaleriesManager->getOneGalerie($galerieId);

                try
                {
//                    Suppression dans la BDD des photos correspondant à la galerie sélectionnée
                    $newPhotosManager->deleteAllPhotosWithGalerieId($galerieId);
                }
                catch (Throwable $exception)
                {
                    Utilitaires::logException($exception);
                    Utilitaires::logMessage("Echec de la suppression d'une ou plusieurs photos");
                    echo "Echec de la suppression d'une ou plusieurs photos";
                }
                try
                {
//                    Suppression dans la BDD de la galerie sélectionnée
                    $newGaleriesManager->deleteGalerie($galerieId);
                }
                catch (Throwable $exception)
                {
                    Utilitaires::logException($exception);
                    Utilitaires::logMessage("Echec de la suppression d'une galerie");
                    echo "Echec de la suppression d'une galerie";
                }



                try
                {
                    $cheminDossierASupprimer = __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie();

                    Utilitaires::deletedirectory($cheminDossierASupprimer);

                    Utilitaires::logMessage("Le dossier a été correctement supprimé");
                }
                catch (Throwable $exception)
                {
                    Utilitaires::logException($exception);
                    Utilitaires::logMessage("Echec de la suppression du dossier de la galerie sur le serveur");
                    echo "Echec de la suppression du dossier de la galerie sur le serveur";
                }
            }
        }
    }
}


