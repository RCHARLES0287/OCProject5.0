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
            /*var_dump($request->postData('checkbox_suppr_photo'));
            exit;*/
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


                $cheminDossierASupprimer = __DIR__ . '/../../../../../Web/images/' . $newGalerieEntity->nom_galerie();

                try
                {
//                    unlink($cheminDossierASupprimer);
                    $suppressionDossier = Utilitaires::deletedirectory($cheminDossierASupprimer);
                    if ($suppressionDossier === true)
                    {
                        Utilitaires::logMessage("Le dossier a été correctement supprimé");
                    }
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

/*
foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath,
                                        FilesystemIterator::SKIP_DOTS),
    RecursiveIteratorIterator::CHILD_FIRST) as $path)
{
    $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());
}
rmdir($dirPath);*/


/*
function deleteTree($dir){
    foreach(glob($dir . "/*") as $element){
        if(is_dir($element)){
            deleteTree($element); // On rappel la fonction deleteTree
            rmdir($element); // Une fois le dossier courant vidé, on le supprime
        } else { // Sinon c'est un fichier, on le supprime
            unlink($element);
        }
        // On passe à l'élément suivant
    }
}

$dir = "path/to/folder";
deleteTree($dir); // On vide le contenu de notre dossier
rmdir($dir); // Et on le supprime*/


/*
function destroy_dir($dir) {
    if (!is_dir($dir) || is_link($dir)) return unlink($dir);
    foreach (scandir($dir) as $file) {
        if ($file == '.' || $file == '..') continue;
        if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) {
            chmod($dir . DIRECTORY_SEPARATOR . $file, 0777);
            if (!destroy_dir($dir . DIRECTORY_SEPARATOR . $file)) return false;
        };
    }
    return rmdir($dir);
} */


/*
function deletedirectory ($dir)
{
    foreach (scandir($dir) as $sousElement)
    {
        if (is_dir($sousElement))
        {
            deletedirectory($sousElement);
        }
        else
        {
            unlink($sousElement);
        }
    }
    return rmdir($dir);
}


$dossierASupprimer = ';;;;;';
$suppressionDossier = deletedirectory($dossierASupprimer);
if ($suppressionDossier === true)
{
    echo "Le dossier a bien été supprimé";
}
*/






/*
$directoryIterator = new RecursiveDirectoryIterator(
    $repertoireASupprimer,
    FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
);
$mainIterator = new RecursiveIteratorIterator(
    $directoryIterator,
    RecursiveIteratorIterator::CHILD_FIRST
);

/** @var SplFileInfo $sousElement */
/*
foreach ($mainIterator as $sousElement) {
    if ($sousElement->isDir()) {
        rmdir($sousElement->getPathname());
    }
    else {
        unlink($sousElement->getPathname());
    }
}
rmdir($repertoireASupprimer);*/


/*
Version ultra compacte

foreach (
    new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($repertoireASupprimer,FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    ) as $sousElement
) {
    ($sousElement->isDir() ? 'rmdir' : 'unlink')($sousElement->getPathname());
}
rmdir($repertoireASupprimer);*/

