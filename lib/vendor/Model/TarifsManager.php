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

    public function getOnePhotoTarifs($photoId)
    {
        $answerTarifsData = $this->db->prepare('SELECT *
                                                        FROM rc_photographe_tarifs
                                                        WHERE tarifs_photo_id=:photoId');
        $answerTarifsData->execute(array(
            'photoId' => $photoId
        ));

        $photoTarifsFeatures = [];

        $dbTarifs = $answerTarifsData->fetchAll();

        foreach ($dbTarifs as $tarif)
        {
            $photoTarifsFeatures [] = new TarifEntity($tarif);
        }
        var_dump($photoTarifsFeatures);
        exit();


        return $photoTarifsFeatures;
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


    public function updateOneTarif(TarifEntity $tarifEntity)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_tarifs
                                            SET tarifs_prix=:newPrice
                                            WHERE tarifs_photo_id=:photoId AND tarifs_dimensions_id=:dimensionsId');
        $req->execute(array(
            'newPrice' => $tarifEntity->prix(),
            'photoId' => $tarifEntity->photo_id(),
            'dimensionsId' => $tarifEntity->dimensions_id()
        ));
    }


    public function deleteOneTarif($photoId, $dimensionsId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_tarifs WHERE tarifs_photo_id=:photoId AND tarifs_dimensions_id=:dimensionsId');
        $req->execute(array(
            'photoId' => $photoId,
            'dimensionsId' => $dimensionsId
        ));
    }

}

