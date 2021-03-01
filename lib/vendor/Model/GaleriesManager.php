<?php


namespace Model;

use RCFramework\Manager;
use Entity\GalerieEntity;
use RCFramework\Utilitaires;

class GaleriesManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllGaleries()
    {
        $answerGaleriesData = $this->db->prepare('SELECT * FROM rc_photographe_galeries ORDER BY galeries_ordre_affichage');
        $answerGaleriesData->execute();

        $galeriesFeatures = [];

        $dbGaleries = $answerGaleriesData->fetchAll();

        foreach ($dbGaleries as $galerie)
        {
            $newGalerie = new GalerieEntity($galerie);
            $galeriesFeatures[$newGalerie->id()] = $newGalerie;
        }

        return $galeriesFeatures;
    }


    public function getOneGalerie($galerieId)
    {
        $answerGalerieData = $this->db->prepare('SELECT * FROM rc_photographe_galeries WHERE galeries_id=:galerieId');
        $answerGalerieData->execute(array(
            'galerieId' => $galerieId
        ));

        $dbGalerie = $answerGalerieData->fetch(\PDO::FETCH_ASSOC);

        $galerieFeatures = new GalerieEntity($dbGalerie);

        return $galerieFeatures;
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

    public function updateGalerie(GalerieEntity $modifiedGalery, $galerieId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_galeries
                                    SET galeries_nom=:galeries_nom, galeries_ordre_affichage=:galeries_ordre_affichage, galeries_chemin_miniature=:galeries_chemin_miniature
                                    WHERE galeries_id=:galeries_id');
        $req->execute(array(
            'galeries_id' => $galerieId,
            'galeries_nom' => $modifiedGalery->nom_galerie(),
            'galeries_ordre_affichage' => $modifiedGalery->ordre_affichage(),
            'galeries_chemin_miniature' => $modifiedGalery->chemin_miniature()
        ));
    }
}