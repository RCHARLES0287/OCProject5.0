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

        if ($controller->getModule() !== 'Connexion' && ConnexionController::isAdminConnected() === false)
        {
            header('Location: /admin/connexion');
            exit;
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}
