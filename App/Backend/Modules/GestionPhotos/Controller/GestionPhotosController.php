<?php

namespace App\Backend\Modules\GestionPhotos\Controller;

use App\Backend\Modules\Connexion\Controller\ConnexionController;
use Entity\PhotoEntity;
use Exception;
use Model\GaleriesManager;
use Model\PhotosManager;
use RCFramework\Application;
use RCFramework\BackController;
use RCFramework\HTTPRequest;

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
            $galerieEntity = $newGalerieManager->getOneGalerie($request->getData('idgalerie'));
            $newPhotoManager = new PhotosManager();
            $selectedGaleriePhotos = $newPhotoManager->getOneGaleriePhotos($request->getData('idgalerie'));


            $galeriePhotosHTML = '';
            foreach ($selectedGaleriePhotos as $photo)
            {
//                    Le .= sert à concaténer la nouvelle chaine à la suite de la variable
//                Attention : pour bien préciser qu'on va transmettre un tableau avec potentiellement plusieurs entrées, à la fin du name on ajoute[]
                $galeriePhotosHTML .= '<div class="photo_dans_galerie">
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


            $photosId = $request->postData('checkbox_suppr_photo');
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
}
/*
<input type="checkbox" class="checkbox_suppr_photo" id="' . $photo->id() .'" name="checkbox_suppr_photo" value="' . $photo->id() . '" />
                                                <label for="' . $photo->id() . '">Supprimer</label>*/
