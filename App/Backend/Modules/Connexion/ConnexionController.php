<?php


namespace App\Backend\Modules\Connexion;


use RCFramework\BackController;

class ConnexionController extends BackController
{
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