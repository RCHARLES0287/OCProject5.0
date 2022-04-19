<?php

namespace App;

use App\Backend\Modules\Connexion\Controller\ConnexionController;
use Model\GaleriesManager;
use RCFramework\Page;

trait TraitLayoutContent
{
    /**
     * @param Page $page
     */
    public function executeLayoutContent($page)
    {

//        Transmet aux vues une variable contenant toutes les galeries
        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $page->addVarIfUndifined('allGaleries', $allGaleries);


        $AdminConnectionStatus = ConnexionController::isAdminConnected();
        $page->addVarIfUndifined('adminConnectionStatus', $AdminConnectionStatus);

    }

}

