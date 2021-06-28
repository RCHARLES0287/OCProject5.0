<?php


namespace App\Backend\Modules\Connexion;


use RCFramework\BackController;
use RCFramework\HTTPRequest;

class ConnexionController extends BackController
{
    public function executeAdminconnexion (HTTPRequest $request)
    {

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