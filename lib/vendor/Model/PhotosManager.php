<?php


namespace Model;


use Entity\PhotoEntity;
use RCFramework\Manager;

class PhotosManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllPhotos()
    {
        $answerPhotosData = $this->db->prepare('SELECT photos_id, photos_galerie_id, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description FROM rc_photographe_photos');
        $answerPhotosData->execute();

        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function getOnePhoto()
    {

    }
}