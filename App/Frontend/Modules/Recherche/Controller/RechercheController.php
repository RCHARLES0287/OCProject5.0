<?php


namespace App\Frontend\Modules\Commande\Controller;


use Model\PhotosManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;


class CommandeController extends BackController
{
    public function executeSendsearchrequest(HTTPRequest $request)
    {
        var_dump('on est dans le controller de la recherche');
        exit;

        if (isset($_GET["send_recherche"]) AND $_GET["send_recherche"] == "Rechercher")
        {
//            Pour sécuriser le formulaire contre les failles html
            $_GET["texte_recherche"] = htmlspecialchars($_GET["texte_recherche"]);
            $texteRecherche = $_GET["texte_recherche"];
//            Pour supprimer les espaces dans la requête de l'internaute
            $texteRecherche = trim($texteRecherche);
//            Pour supprimer les balises html dans la requête
            $texteRecherche = strip_tags($texteRecherche);
//            Pour passer la chaîne de caractères en minuscules
            $texteRecherche = strtolower($texteRecherche);

            $newPhotosManager = new PhotosManager();
            $matchingPhotos = $newPhotosManager->getAllPhotosFromTexteRecherche($texteRecherche);

            var_dump($matchingPhotos);
            exit;

        }
    }
}

