<?php


namespace App\Backend\Modules\Connexion\Controller;


use Model\AdministrateurManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;

class ConnexionController extends BackController
{
    public function executeAdminconnexion (HTTPRequest $request)
    {

    }

    public function executeAdminconnexionform (HTTPRequest $request)
    {
        if (!Utilitaires::emptyMinusZero($request->postData('email_adress'))
            && !Utilitaires::emptyMinusZero($request->postData('password')))
        {
            $AdministrateurManager = new AdministrateurManager();
            $SQLRequest = 'SELECT admins_login, admins_password FROM rc_photographe_admins WHERE admins_login=:adminLogin';
            $administrateurEntity = $AdministrateurManager->compareIdentificationWithDb($request->postData('email_adress'),
                                                                                    $request->postData('password'),
                                                                                    $SQLRequest,
                                                                                    'adminLogin');

            if ($administrateurEntity !== null)
            {
                $_SESSION['administrateur_entity'] = $administrateurEntity;
            }
            else
            {
                throw new \Exception('Erreur dans l\'authentification de l\'administrateur');
            }
        }
    }


    static public function isConnected()
    {
        if ($_SESSION['connexion_status'] === 'connected')
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
