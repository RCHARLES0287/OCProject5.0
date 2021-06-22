<?php


namespace App\Frontend\Modules\Identification\Controller;

use Entity\UtilisateurEntity;
use Model\UtilisateursManager;
use RCFramework\HTTPRequest;

class IdentificationController extends \RCFramework\BackController
{
    public function executeLogginginform (HTTPRequest $request)
    {

    }


    public function executeLoggingin (HTTPRequest $request)
    {
        $UtilisateurManager = new UtilisateursManager();
        $utilisateurEntity = $UtilisateurManager->compareVisitorWithDb($request->postData('email_adress'), $request->postData('password'));

        if ($utilisateurEntity !== null)
        {
            /*
            $_SESSION['connexion_status'] = 'connected';
            $_SESSION['login'] = $utilisateurEntity->email();
            */
            $_SESSION['utilisateur_entity'] = $utilisateurEntity;
//            Cette autre méthode serait utilisée pour passer le prénom dans une variable utilisable avec Twig dans la vue si on n'avait pas fourni le $_SESSION en global à Twig dans le fichier page.php
//            $this->page->addVar('prenomUtilisateur', $_SESSION['utilisateur_entity']->prenom());


        }
        if ($request->postExists('marqueur_validation_panier'))
        {
            header('Location: /validationpanier');
        }

    }


    public function executeLoggingoutform (HTTPRequest $request)
    {

    }


    public function executeLoggingout (HTTPRequest $request)
    {
        unset($_SESSION['utilisateur_entity']);
        header('Location: /showallavailablephotos');
    }



    public function executeSigningupform (HTTPRequest $request)
    {

    }


    public function executeSigningup (HTTPRequest $request)
    {
        if (isset($_POST['prenom']) && isset($_POST['nom']) && isset($_POST['birthdate']) && isset($_POST['numero_rue']) && isset($_POST['nom_rue'])
            && isset($_POST['code_postal']) && isset($_POST['ville']) && isset($_POST['pays']) && isset($_POST['email_adress']) && isset($_POST['password']))
        {
            $newUtilisateurEntity = new UtilisateurEntity();
            $newUtilisateurEntity->setPrenom($_POST['prenom']);
            $newUtilisateurEntity->setNom($_POST['nom']);
            $newUtilisateurEntity->setBirthdate($_POST['birthdate']);
            $newUtilisateurEntity->setNumero_rue($_POST['numero_rue']);
            $newUtilisateurEntity->setNom_rue($_POST['nom_rue']);
            $newUtilisateurEntity->setComplement_adresse($_POST['complement_adresse']);
            $newUtilisateurEntity->setCode_postal($_POST['code_postal']);
            $newUtilisateurEntity->setVille($_POST['ville']);
            $newUtilisateurEntity->setPays($_POST['pays']);
            $newUtilisateurEntity->setTelephone($_POST['telephone']);
            $newUtilisateurEntity->setEmail($_POST['email_adress']);
            $newUtilisateurEntity->setPassword(password_hash($_POST['password'], PASSWORD_DEFAULT));

            $newUtilisateurManager = new UtilisateursManager();
            $newUtilisateurManager->saveOneUtilisateur($newUtilisateurEntity);

        }
    }
}
