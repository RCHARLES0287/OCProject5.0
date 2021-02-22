<?php


namespace Model;


use Entity\PhotoEntity;
use PDO;
use RCFramework\Manager;

class PhotosManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPhotos()
    {
        $answerPhotosData = $this->db->prepare('SELECT photos_id, photos_galerie_id, photos_ordre_carousel, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description FROM rc_photographe_photos');
        $answerPhotosData->execute();

        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function getOnePhoto($photoId)
    {
        $answerPhotoData = $this->db->prepare('SELECT photos_id, photos_galerie_id, photos_ordre_carousel, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description FROM rc_photographe_photos WHERE photos_id=:photoId');
        $answerPhotoData->execute(array(
            'photoId' => $photoId
        ));

        $dbPhoto =$answerPhotoData->fetch();

        if ($dbPhoto === false)
        {
            throw new \Exception("Aucune photo ne porte l'Id " . $photoId);
        }
        else
        {
            return new PhotoEntity($dbPhoto);
        }
    }

    public function getOneGaleriePhotos($galerieId)
    {
        $answerPhotosData = $this->db->prepare('SELECT photos_id, photos_galerie_id, photos_ordre_carousel, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description FROM rc_photographe_photos WHERE photos_galerie_id=:galerieId');
        $answerPhotosData->execute(array(
            'galerieId' => $galerieId
        ));

        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function saveOnePhoto(PhotoEntity $newPhotoEntity)
    {
        $testPhotoExist = $this->checkPhotoSerialNumber($newPhotoEntity);
        if ($testPhotoExist === true)
        {
            $req = $this->db->prepare('INSERT INTO rc_photographe_photos(photos_galerie_id, photos_ordre_carousel, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description) VALUES(:galerieId, :ordreCarousel, :photoSerialNumber, :photoName, :photoType, :lieu, :description)');
            $req->execute(array(
                'galerieId' => $newPhotoEntity->galerie_id(),
                'ordreCarousel' => $newPhotoEntity->,
                'photoSerialNumber' => $newPhotoEntity->serial_number(),
                'photoName' => $newPhotoEntity->name(),
                'photoType' => $newPhotoEntity->type_id(),
                'lieu' => $newPhotoEntity->lieu(),
                'description' => $newPhotoEntity->description()
            ));
        }
        else
        {
            throw new \Exception("Une photo porte déjà ce numéro de série");
        }
    }

    public function updateOnePhoto(PhotoEntity $photoEntity, $photoId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_photos
                                            SET photos_galerie_id=:galerieId, photos_serial_number=:serialNumber, photos_name=:photoName, photos_type_id=:typeId, photos_lieu=:lieu, photos_description=:description
                                            WHERE photos_id=:photoId');
        $req->execute(array(
            'photoId' => $photoId,
            'galerieId' => $photoEntity->galerie_id(),
            'serialNumber' => $photoEntity->serial_number(),
            'photoName' => $photoEntity->name(),
            'typeId' => $photoEntity->type_id(),
            'lieu' => $photoEntity->lieu(),
            'description' => $photoEntity->description()
        ));
    }

    public function deleteOnePhoto($photoId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_photos WHERE photos_id=:photoId');
        $req->execute(array(
            'photoId' => $photoId
        ));
    }

    public function checkPhotoSerialNumber(PhotoEntity $photoEntity)
    {

        $dbSerialNumber = $this->db->prepare("SELECT photos_serial_number FROM rc_photographe_photos WHERE photos_serial_number=:photoSerialNumber");

        $dbSerialNumber->bindValue('photoSerialNumber', $photoEntity->serial_number(), \PDO::PARAM_STR);
        $dbSerialNumber->execute();

        $serialNumberTest = $dbSerialNumber->fetch(PDO::FETCH_COLUMN);
//        Le test se fait avec un "triple =" pour vérifier à la fois la valeur ET le type afin de ne pas matcher avec la valeur 0
        if ($serialNumberTest === false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}