<?php


namespace App\Backend\Templates;


use RCFramework\Application;
use RCFramework\BackController;

class BackendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Backend';
    }

    public function run()
    {

        /** @var BackController $controller */
        $controller = $this->getController();

        if ($controller->getModule() !== 'Connexion' && ConnexionController::isConnected() === false)
        {
            header('Location: /admin/errorauthentification');
            exit;
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}