<?php


namespace Model;


use Entity\TarifEntity;
use RCFramework\Manager;

class TarifsManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllTarifs($photoId)
    {
        $answerTarifsData = $this->db->prepare('SELECT tarifs_photo_id, tarifs_dimensions_id, tarifs_prix 
                                                        FROM rc_photographe_tarifs 
                                                        WHERE tarifs_photo_id=:photoId
                                                        ORDER BY tarifs_prix');
        $answerTarifsData->execute(array(
            'photoId' => $photoId
        ));

        $tarifsFeatures = [];

        $dbTarifs = $answerTarifsData->fetchAll();

        foreach ($dbTarifs as $tarif)
        {
            $tarifsFeatures[] = new TarifEntity($tarif);
        }

        return $tarifsFeatures;
    }


    public function saveOneTarif(TarifEntity $newTarifEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_tarifs(tarifs_photo_id, tarifs_dimensions_id, tarifs_prix) VALUES (:tarifs_photo_id, :tarifs_dimensions_id, :tarifs_prix)');
        $req->execute(array(
            'tarifs_photo_id' => $newTarifEntity->photo_id(),
            'tarifs_dimensions_id' => $newTarifEntity->dimensions_id(),
            'tarifs_prix' => $newTarifEntity->prix()
        ));
    }


    public function deleteOneTarif($tarifId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_tarifs WHERE ');
    }
}