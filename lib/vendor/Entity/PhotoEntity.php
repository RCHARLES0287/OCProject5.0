<?php


namespace Entity;


use Model\GaleriesManager;
use RCFramework\Entity;
use RCFramework\Utilitaires;

class PhotoEntity extends Entity
{
//    CLASS_PREFIX contient le préfixe de la table
    protected const CLASS_PREFIX = 'photos';
    private ?int $galerie_id;
    private ?int $ordre_carousel;
    private string $serial_number;
    private ?string $name;
    private ?int $type_id;
    private ?string $lieu;
    private ?string $description;

    public const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'gif', 'png'];

    public function setGalerie_id($galerie_id)
    {
        if (Utilitaires::emptyMinusZero($galerie_id))
        {
            $this->galerie_id = null;
        }
        else
        {
            $this->galerie_id = $galerie_id;
        }
    }

    public function galerie_id():?int
    {
        return $this->galerie_id;
    }

    public function chemin_photo():string
    {
        $newGalerieManager = new GaleriesManager();
        $newGalerieEntity = $newGalerieManager->getOneGalerie($this->galerie_id());
        $galerieName = $newGalerieEntity->nom_galerie();

        return $galerieName . '/' . $this->serial_number();
    }


    public function setOrdre_carousel($ordre_carousel)
    {
        if (Utilitaires::emptyMinusZero($ordre_carousel))
        {
            $this->ordre_carousel = null;
        }
        else
        {
            $this->ordre_carousel = $ordre_carousel;
        }
    }

    public function ordre_carousel():?int
    {
        return $this->ordre_carousel;
    }


    public function setSerial_number($serial_number)
    {
        if (Utilitaires::emptyMinusZero($serial_number))
        {
            throw new \Exception("Le numéro de série de la photo doit être défini");
        }
        else
        {
            $this->serial_number = $serial_number;
        }
    }

    public function serial_number():string
    {
        return $this->serial_number;
    }


    public function setName($name)
    {
        if (Utilitaires::emptyMinusZero($name))
        {
            $this->name = null;
        }
        else
        {
            $this->name = $name;
        }
    }

    public function name():?string
    {
        return $this->name;
    }


    public function setType_id($type_id)
    {
        if (Utilitaires::emptyMinusZero($type_id))
        {
            $this->type_id = null;
        }
        else
        {
            $this->type_id = $type_id;
        }
    }

    public function type_id():?int
    {
        return $this->type_id;
    }


    public function setLieu($lieu)
    {
        if (Utilitaires::emptyMinusZero($lieu))
        {
            $this->lieu = null;
        }
        else
        {
            $this->lieu = $lieu;
        }
    }

    public function lieu():?string
    {
        return $this->lieu;
    }


    public function setDescription($description)
    {
        if (Utilitaires::emptyMinusZero($description))
        {
            $this->description = null;
        }
        else
        {
            $this->description = $description;
        }
    }

    public function description():?string
    {
        return $this->description;
    }

    public function cheminPhoto():?string
    {
        $newGalerieManager = new GaleriesManager();
        $newGalerieEntity = $newGalerieManager->getOneGalerie($this->galerie_id);

        return '/images/' . $newGalerieEntity->nom_galerie() . '/' . $this->serial_number;
    }
}
