<?php


namespace App\Frontend\Modules\Identification\Controller;

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

        if ($utilisateurEntity !== false)
        {
            $_SESSION['connexion_status'] = 'connected';
            $_SESSION['login'] = $utilisateurEntity->email();
        }
    }
}
