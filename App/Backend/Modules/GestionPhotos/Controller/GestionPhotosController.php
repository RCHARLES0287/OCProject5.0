<?php

namespace App\Backend\Modules\GestionPhotos\Controller;

use App\Backend\Modules\Connexion\Controller\ConnexionController;
use Entity\DimensionsEntity;
use Entity\PhotoEntity;
use Entity\TarifEntity;
use Exception;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\PhotosManager;
use Model\TarifsManager;
use RCFramework\Application;
use RCFramework\BackController;
use RCFramework\Entity;
use RCFramework\HTTPRequest;
use RCFramework\NonexistantEntityException;
use RCFramework\Utilitaires;

class GestionPhotosController extends BackController
{
    public function executeAddonephoto(HTTPRequest $request)
    {
        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $this->page->addVar('allGaleries', $allGaleries);
    }


    public function executeOrdrecarousel(HTTPRequest $request)
    {
        /*var_dump('entrée dans controller de ordrecarousel');
        exit;*/


        $newPhotosManager = new PhotosManager();
        $allPhotos = $newPhotosManager->getAllPhotos();

        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $this->page->addVar('allPhotos', $allPhotos);
        $this->page->addVar('allGaleries', $allGaleries);

//        var_dump($allPhotos);
//        var_dump($allGaleries);
//        exit;


    }

    public function executeValidateordrecarousel(HTTPRequest $request)
    {
        for ($zoneCarousel=1; $zoneCarousel<6; $zoneCarousel++)
        {
            if (isset($_POST['input_' . $zoneCarousel]))
            {
                $carouselZoneiIdPhoto = $_POST['input_' . $zoneCarousel];
                $newPhotoManager = new PhotosManager();
                $newPhotoManager->updateOrdreCarousel($carouselZoneiIdPhoto, $zoneCarousel);
            }
        }

        header('Location: /admin/accueiladmin');
    }


    public function executeAjoutphotoform(HTTPRequest $request)
    {
        if (!isset($_POST['galerie_id'])
            || !isset($_POST['photo_name'])
            || !isset($_POST['type_photo'])
            || !isset($_POST['choix_photo_lieu'])
            || !isset($_POST['photo_description'])
            || !isset($_FILES['choix_photo'])
            || $_FILES['choix_photo']['error'] != 0)
        {
            throw new Exception("Erreur dans le remplissage du formulaire d'ajout de photo");
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
                $fileInfos = pathinfo($_FILES['choix_photo']['name']);
                $extension = strtolower($fileInfos['extension']);

                if (!in_array($extension, PhotoEntity::ALLOWED_EXTENSIONS))
                {
                    throw new Exception("Echec : extension non supportée");
                }
                else
                {
                    $newPhotoEntity = new PhotoEntity();
                    $newPhotoEntity->setGalerie_id($_POST['galerie_id']);
                    $newPhotoEntity->setOrdre_carousel(null);
                    $newPhotoEntity->setSerial_number($_FILES['choix_photo']['name']);
                    $newPhotoEntity->setName($_POST['photo_name']);
                    $newPhotoEntity->setType_id($_POST['type_photo']);
                    $newPhotoEntity->setLieu($_POST['choix_photo_lieu']);
                    $newPhotoEntity->setDescription($_POST['photo_description']);

                    $newGalerieManager = new GaleriesManager();
                    $newGalerieEntity = $newGalerieManager->getOneGalerie($_POST['galerie_id']);

                    $uploadPhoto = move_uploaded_file($_FILES['choix_photo']['tmp_name'],
                        __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie() . '/' . $newPhotoEntity->serial_number());

                    if ($uploadPhoto != true)
                    {
                        throw new Exception("Echec de l'enregistrement de la photo");
                    }
                    else
                    {
                        $newPhotoManager = new PhotosManager();
                        $newPhotoManager->saveOnePhoto($newPhotoEntity);
                    }
                }
            }
        }
    }

    public function executeSuppressionphotochoixgalerie (HTTPRequest $request)
    {
        if ($request->getExists('idgalerie'))
        {
            $newGalerieManager = new GaleriesManager();
            $galerieEntity = $newGalerieManager->getOneGalerie($request->dataGet('idgalerie'));
            $newPhotoManager = new PhotosManager();
            $selectedGaleriePhotos = $newPhotoManager->getOneGaleriePhotos($request->dataGet('idgalerie'));


            $galeriePhotosHTML = '';
            foreach ($selectedGaleriePhotos as $photo)
            {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
//                Attention : pour bien préciser qu'on va transmettre un tableau avec potentiellement plusieurs entrées, à la fin du name on ajoute[]
                $galeriePhotosHTML .= '<div class="photo_dans_galerie photo_dans_mosaique">
                                                <div class="descriptif_photo">' . $photo->serial_number() . ' : ' . $photo->lieu() . '</div>
                                                <img alt="description" src="/images/' . $galerieEntity->nom_galerie() . '/' . $photo->serial_number() .'">
                                                
                                                <input type="checkbox" class="checkbox_suppr_photo" id="' . $photo->id() .'" name="checkbox_suppr_photo[]" value="' . $photo->id() . '" />
                                                <label for="' . $photo->id() . '">Supprimer</label>
                                       </div>';
            }

//            Bien mettre le header avant le echo. Cela évite une erreur si une partie des données est déjà en cours d'envoi.
            header('Content-Type: text/html; charset=utf-8');
            echo $galeriePhotosHTML;
            exit;
        }

        $newGalerieManager = new GaleriesManager();
        $allGaleries = $newGalerieManager->getAllGaleries();

        $this->page->addVar('all_galeries', $allGaleries);
    }

    public function executeConfirmationsuppressionphoto (HTTPRequest $request)
    {
        if (!$request->postExists('checkbox_suppr_photo'))
        {
            throw new Exception("Vous n'avez sélectionné aucune photo");
        }
        else
        {
            /*var_dump($request->postData('checkbox_suppr_photo'));
            exit;*/
            $newPhotoManager = new PhotosManager();
            $newGalerieManager = new GaleriesManager();


            $photosId = $request->dataPost('checkbox_suppr_photo');
            foreach ($photosId as $photoId)
            {
                $newPhotoEntity = $newPhotoManager->getOnePhoto($photoId);
                $newGalerieEntity = $newGalerieManager->getOneGalerie($newPhotoEntity->galerie_id());

                $fichierASupprimer = __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie() . '/' . $newPhotoEntity->serial_number();

                $newPhotoManager->deleteOnePhoto($photoId);
                unlink($fichierASupprimer);

            }
        }
    }


    public function executeGestiondimensions (HTTPRequest $request)
    {
        $dimensionsManager = new DimensionsManager();
        $allDimensions = $dimensionsManager->getAllDimensions();

        /*var_dump($allDimensions);
        exit;*/

        $this->page->addVar('alldimensions', $allDimensions);
    }

    public function executeDeletedimensions (HTTPRequest $request)
    {
        if ($request->getExists('dim_id'))
        {
            /*var_dump($request->dataGet('dim_id'));
            exit;*/

            $dimensionsManager = new DimensionsManager();

            try
            {
                $dimensionsManager->deleteDimensions($request->dataGet('dim_id'));
            }
            catch (Exception $e)
            {
                Utilitaires::logException($e);
                Utilitaires::logMessage("Echec de la suppression des dimensions sélectionnées");
                echo "Echec de la suppression des dimensions sélectionnées";
            }
        }
        header('Location: /admin/gestiondimensions');
        exit;
    }


    public function executeAdddimensions (HTTPRequest $request)
    {
        /*var_dump('On est dans le controller');
        exit;*/

        if ($request->postExists('new_dimensions'))
        {
            /*var_dump('POST ok');
            exit;*/

            $dimensionEntity = new DimensionsEntity();
            $dimensionEntity->setDimensions($request->dataPost('new_dimensions'));

            $dimensionsManager = new DimensionsManager();

            /*var_dump($dimensionEntity->dimensions());
            exit;*/

            try
            {
                $dimensionsManager->saveDimensions($dimensionEntity);

                /*var_dump('manager ok');
                exit;*/

            }
            catch (Exception $e)
            {
                Utilitaires::logException($e);
                Utilitaires::logMessage("Echec de l'ajout des dimensions sélectionnées");
                echo "Echec de l'ajout des dimensions sélectionnées";
            }
        }
        header('Location: /admin/gestiondimensions');
        exit;
    }


    /*public function executeGestiontarifs (HTTPRequest $request)
    {
        $newTarifsManager = new TarifsManager();
        $allTarifs = $newTarifsManager->getAllTarifs();

        var_dump($allTarifs);
        exit;


    }*/


    public function executeGestiontarifsphotos (HTTPRequest $request)
    {
        $photosManager = new PhotosManager();
        $allPhotos = $photosManager->getAllPhotos();

        $dimensionsManager = new DimensionsManager();
        $allDimensions = $dimensionsManager->getAllDimensions();

        $tarifsManager = new TarifsManager();
        $allTarifs = $tarifsManager->getAllTarifs();

        $this->page->addVar('all_photos', $allPhotos);
        $this->page->addVar('all_dimensions', $allDimensions);
        $this->page->addVar('all_tarifs', $allTarifs);

        /*var_dump($allTarifs);
        exit;*/
    }


    /**
     * @param HTTPRequest $request
     * @throws Exception
     */
    public function executeGetOneTarif (HTTPRequest $request)
    {
        if ($request->getExists('id_photo') && ($request->getExists('id_dimensions')))
        {
            /*Utilitaires::logMessage($request->dataGet('id_photo'));
            Utilitaires::logMessage($request->dataGet('id_dimensions'));*/

            $tarifsManager = new TarifsManager();

            try
            {
                $tarifEntity = $tarifsManager->getOnePhotoAndDimensionsTarif($request->dataGet('id_photo'), $request->dataGet('id_dimensions'));
                $resultTarif = json_encode($tarifEntity->prix(), JSON_THROW_ON_ERROR);
            }
            catch (NonexistantEntityException $exception)
            {
                $resultTarif = json_encode('', JSON_THROW_ON_ERROR);
            }

            Utilitaires::logMessage($resultTarif);

//            Bien mettre le header avant le echo. Cela évite une erreur si une partie des données est déjà en cours d'envoi.
            header('Content-Type: application/json; charset=utf-8');
            echo $resultTarif;
            exit;
        }
    }



    public function executeChangetarifsphotos (HTTPRequest $request)
    {
        if ($request->postExists('dimensions')
            && $request->postExists('id_photo')
            && $request->postExists('tarif_associe')
            && $request->postExists('marqueur_save_vs_delete'))
        {
            $newTarifEntity = new TarifEntity();
            $newTarifEntity->setDimensions_id($request->dataPost('dimensions'));
            $newTarifEntity->setPhoto_id($request->dataPost('id_photo'));
            $newTarifEntity->setPrix($request->dataPost('tarif_associe'));

            $tarifsManager = new TarifsManager();

            switch ($request->dataPost('marqueur_save_vs_delete'))
            {
                case 'save' :
                    try
                    {
//                        Cas du succès de l'enregistrement du nouveau tarif
                        $tarifsManager->saveOneTarif($newTarifEntity);

                        header('Location: /admin/gestiontarifsphotos?message_retour_modification_tarif=1');
                        exit;
                    }
                    catch (Exception $exception)
                    {
//                        Cas de l'échec de l'enregistrement du nouveau tarif
//                        echo "Echec de l'enregistrement du nouveau tarif dans la BDD";
                        Utilitaires::logException($exception);
                        header('Location: /admin/gestiontarifsphotos?message_retour_modification_tarif=2');
                    }
                    break;
                case 'delete' :
                    try
                    {
//                        Cas du succès de la suppression d'un tarif
                        $tarifsManager->deleteOneTarif($newTarifEntity);

                        header('Location: /admin/gestiontarifsphotos?message_retour_modification_tarif=3');
                        exit;
                    }
                    catch (Exception $exception)
                    {
//                        Cas de l'échec de la suppression d'un tarif
                        Utilitaires::logException($exception);
                        header('Location: /admin/gestiontarifsphotos?message_retour_modification_tarif=4');
                    }
            }
        }
        else
        {
            throw new Exception("Paramètre(s) manquant(s) pour l'enregistrement du nouveau tarif");
        }

        header('Location: /admin/gestiontarifsphotos');
        exit;
    }

}






