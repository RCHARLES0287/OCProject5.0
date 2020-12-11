<?php


namespace Model;

use RCFramework\Managers;
use Entity\GalerieEntity;

class GaleriesManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllGaleries()
    {
        $answerGaleriesData = $this->db->prepare('SELECT galeries_id, galeries_nom, galeries_ordre_affichage, galeries_chemin_miniature FROM rc_photographe_galeries ORDER BY galeries_ordre_affichage');
        $answerGaleriesData->execute();

        $galeriesFeatures = [];

        $dbGaleries = $answerGaleriesData->fetchAll();

        foreach ($dbGaleries as $galerie)
        {
            $galeriesFeatures[] =new GalerieEntity($galerie);
        }

        return $galeriesFeatures;
    }


    public function saveGalerie(GalerieEntity $newGalerieEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_galeries(galeries_nom, galeries_ordre_affichage, galeries_chemin_miniature) VALUES (:galeries_nom, :galeries_ordre_affichage, :galeries_chemin_miniature)');
        $req->execute(array(
            'galeries_nom' => $newGalerieEntity->nom_galerie(),
            'galeries_ordre_affichage' => $newGalerieEntity->ordre_affichage(),
            'galeries_chemin_miniature' => $newGalerieEntity->chemin_miniature()
        ));
    }


    public function deleteGalerie($galerieId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_galeries WHERE galeries_id=:galerieId');
        $req->bindValue('galerieId', $galerieId, \PDO::PARAM_INT);
        $req->execute();
    }
}