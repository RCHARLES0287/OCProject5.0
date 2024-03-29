<?php


namespace Model;


use Entity\PhotoEntity;
use PDO;
use RCFramework\Manager;
use RCFramework\Utilitaires;

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

    public function getOneGaleriePhotos($galerieId):array
    {
        $answerPhotosData = $this->db->prepare('SELECT * FROM rc_photographe_photos WHERE photos_galerie_id=:galerieId');
        $answerPhotosData->bindValue('galerieId', $galerieId, PDO::PARAM_INT);
        $answerPhotosData->execute();

        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function getOnePhoto($photoId): PhotoEntity
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

    public function getOneGalerieNumberOfPhotos($galerieId)
    {
        $answerNumberOfPhotosData = $this->db->prepare('SELECT count(*) FROM rc_photographe_photos WHERE photos_galerie_id=:galerieId');
        $answerNumberOfPhotosData->execute(array(
            'galerieId' => $galerieId
        ));

        return $answerNumberOfPhotosData->fetchColumn();
    }

    public function getOneGaleriePhotosWithPageNumber($galerieId, $pageNumber):array
    {

        /*var_dump($galerieId);
        var_dump($pageNumber);
        exit;*/

        $answerPhotosData = $this->db->prepare('SELECT * 
                                                        FROM rc_photographe_photos 
                                                        WHERE photos_galerie_id=:galerieId 
                                                        ORDER BY photos_id
                                                        LIMIT :photos_par_page
                                                        OFFSET :nombre_photos_a_ignorer');

        /*$answerPhotosData = $this->db->prepare('SELECT *
                                                        FROM rc_photographe_photos 
                                                        WHERE photos_galerie_id=:galerieId 
                                                        ORDER BY photos_id
                                                        LIMIT 6
                                                        OFFSET 6');*/
        $answerPhotosData->bindValue('galerieId', $galerieId, PDO::PARAM_INT);
        $answerPhotosData->bindValue('photos_par_page', Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES, PDO::PARAM_INT);
        $answerPhotosData->bindValue('nombre_photos_a_ignorer', Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES * ($pageNumber - 1), PDO::PARAM_INT);
        $answerPhotosData->execute();

        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();


        /*var_dump($dbPhotos);
        exit;*/



        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function getAllAvailablePhotos()
    {
        $answerPhotosData = $this->db->prepare('SELECT *
                                                        FROM rc_photographe_photos
                                                        WHERE EXISTS(
                                                            SELECT *
                                                            FROM rc_photographe_tarifs
                                                            WHERE photos_id = tarifs_photo_id)'
                                                        );
        $answerPhotosData->execute();
        $photosFeatures = [];

        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }


    public function getNombrePhotosFromTexteRecherche($texteRecherche)
    {
        $answerPhotosData = $this->db->prepare('SELECT COUNT(*)
                                                    FROM rc_photographe_photos
                                                    WHERE (photos_name LIKE :critereRecherche
                                                    OR photos_lieu LIKE :critereRecherche
                                                    OR photos_description LIKE :critereRecherche)
                                                    ');
        $answerPhotosData->bindValue('critereRecherche', '%' . $texteRecherche . '%', PDO::PARAM_STR);
        $answerPhotosData->execute();

        return $answerPhotosData->fetchColumn();
    }

    public function getAllPhotosFromTexteRechercheWithPageNumber($texteRecherche, $pageNumber): array
    {
        $answerPhotosData = $this->db->prepare('SELECT *
                                                    FROM rc_photographe_photos
                                                    WHERE (photos_name LIKE :critereRecherche
                                                    OR photos_lieu LIKE :critereRecherche
                                                    OR photos_description LIKE :critereRecherche)
                                                    ORDER BY photos_id
                                                    LIMIT :photos_par_page
                                                    OFFSET :nombre_photos_a_ignorer');
        $answerPhotosData->bindValue('critereRecherche', '%' . $texteRecherche . '%', PDO::PARAM_STR);
        $answerPhotosData->bindValue('photos_par_page', Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES, PDO::PARAM_INT);
        $answerPhotosData->bindValue('nombre_photos_a_ignorer', Utilitaires::NOMBRE_PHOTOS_PAR_PAGE_GALERIES * ($pageNumber - 1), PDO::PARAM_INT);

        $answerPhotosData->execute();
        $photosFeatures = [];

        /*
        Equivaut au bloc while juste en dessous, mais avec une étape (et une variable supplémentaire)
        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $photo)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }
        */

        while (($photo = $answerPhotosData->fetch())!== false)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function getSamplePhotosFromAutocompleteRecherche($texteRecherche)
    {
        $answerPhotosData = $this->db->prepare('SELECT *
                                                    FROM rc_photographe_photos
                                                    WHERE (photos_name LIKE :critereRecherche
                                                    OR photos_lieu LIKE :critereRecherche
                                                    OR photos_description LIKE :critereRecherche)
                                                    ORDER BY photos_id
                                                    LIMIT :photos_par_page');
        $answerPhotosData->bindValue('critereRecherche', '%' . $texteRecherche . '%', PDO::PARAM_STR);
        $answerPhotosData->bindValue('photos_par_page', Utilitaires::NOMBRE_RESULTATS_RECHERCHE_AUTOCOMPLETE, PDO::PARAM_INT);

        $answerPhotosData->execute();
        $photosFeatures = [];

        while (($photo = $answerPhotosData->fetch())!== false)
        {
            $photosFeatures[] = new PhotoEntity($photo);
        }

        return $photosFeatures;
    }

    public function saveOnePhoto(PhotoEntity &$newPhotoEntity)
    {
        $testPhotoExist = $this->checkPhotoSerialNumber($newPhotoEntity);
        if ($testPhotoExist === true)
        {
            $req = $this->db->prepare('INSERT INTO rc_photographe_photos(photos_galerie_id, photos_ordre_carousel, photos_serial_number, photos_name, photos_type_id, photos_lieu, photos_description) VALUES(:galerieId, :ordreCarousel, :photoSerialNumber, :photoName, :photoType, :lieu, :description)');
            $req->execute(array(
                'galerieId' => $newPhotoEntity->galerie_id(),
                'ordreCarousel' => $newPhotoEntity->ordre_carousel(),
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
        //        lastInsertId sert à alimenter automatiquement l'id de l'entité qui a été passée en paramètre suite à sa création dans la BDD
        $newPhotoEntity->setId($this->db->lastInsertId());
    }

    public function updateOnePhoto(PhotoEntity $photoEntity, $photoId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_photos
                                            SET photos_galerie_id=:galerieId, photos_ordre_carousel=:ordreCarousel, photos_serial_number=:serialNumber, photos_name=:photoName, photos_type_id=:typeId, photos_lieu=:lieu, photos_description=:description
                                            WHERE photos_id=:photoId');
        $req->execute(array(
            'photoId' => $photoId,
            'galerieId' => $photoEntity->galerie_id(),
            'ordreCarousel' => $photoEntity->ordre_carousel(),
            'serialNumber' => $photoEntity->serial_number(),
            'photoName' => $photoEntity->name(),
            'typeId' => $photoEntity->type_id(),
            'lieu' => $photoEntity->lieu(),
            'description' => $photoEntity->description()
        ));
    }

    public function updateOrdreCarousel($photoId, $ordreCarousel)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_photos
                                            SET photos_ordre_carousel=null
                                            WHERE photos_ordre_carousel=:ordreCarousel');
        $req->execute(array(
            'ordreCarousel' => $ordreCarousel
        ));

        $req = $this->db->prepare('UPDATE rc_photographe_photos
                                            SET photos_ordre_carousel=:newOrdreCarousel
                                            WHERE photos_id=:photoId');
        $req->execute(array(
            'photoId' => $photoId,
            'newOrdreCarousel' => $ordreCarousel
        ));
    }

    public function deleteOnePhoto($photoId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_photos WHERE photos_id=:photoId');
        $req->execute(array(
            'photoId' => $photoId
        ));
    }


    public function deleteAllPhotosWithGalerieId ($galerieId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_photos WHERE photos_galerie_id=:galerieId');
        $req->bindValue('galerieId', $galerieId, PDO::PARAM_INT);
        $req->execute();
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


    public function getAllPhotosForCarousel(): array
    {
        $photosFeatures = [];
        $answerPhotosData = $this->db->prepare('SELECT * FROM rc_photographe_photos WHERE photos_ordre_carousel IS NOT NULL ORDER BY photos_ordre_carousel ASC');

        $answerPhotosData->execute();
        $dbPhotos = $answerPhotosData->fetchAll();

        foreach ($dbPhotos as $dbPhoto)
        {
            $photosFeatures [] = new PhotoEntity($dbPhoto);
        }

        return $photosFeatures;
    }
}


