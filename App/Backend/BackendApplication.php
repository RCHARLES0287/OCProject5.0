<?php


namespace App\Backend;


use App\Backend\Modules\Connexion\Controller\ConnexionController;
use App\TraitLayoutContent;
use RCFramework\Application;
use RCFramework\BackController;

class BackendApplication extends Application
{
    use TraitLayoutContent;

    public function __construct()
    {
        parent::__construct();

        $this->name = 'Backend';
    }

    public function run()
    {

        /** @var BackController $controller */
        $controller = $this->getController();

        ///todo Dé-commenter le bloc ci-dessous et faire le nécessaire pour gérer la connexion à l'admin
        /*if ($controller->getModule() !== 'Connexion' && ConnexionController::isConnected() === false)
        {
            header('Location: /admin/errorauthentification');
            exit;
        }*/

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}