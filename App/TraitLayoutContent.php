<?php

namespace App;

use Model\GaleriesManager;

trait TraitLayoutContent
{
    public function executeLayoutContent($page)
    {

        $newGaleriesManager = new GaleriesManager();
        $allGaleries = $newGaleriesManager->getAllGaleries();

        $page->addVar('allGaleries', $allGaleries);

    }

}

