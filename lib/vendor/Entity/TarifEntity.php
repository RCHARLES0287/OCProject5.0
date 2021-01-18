<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class TarifEntity extends Entity
{
    private int $photo_id;
    private int $dimensions_id;
    private float $prix;

    public function setPhoto_id(int $photo_id)
    {
        if (Utilitaires::emptyMinusZero($photo_id))
        {
            throw new \Exception("L'id de la photo doit être défini");
        }
        else
        {
            $this->photo_id = $photo_id;
        }
    }

    public function photo_id():int
    {
        return $this->photo_id;
    }


    public function setDimensions_id(int $dimensions_id)
    {
        if (Utilitaires::emptyMinusZero($dimensions_id))
        {
            throw new \Exception("Les dimensions de la photo doivent être définies");
        }
    }

    public function dimensions_id():int
    {
        return $this->dimensions_id;
    }


    public function setPrix(float $prix)
    {
        if (Utilitaires::emptyMinusZero($prix))
        {
            throw new \Exception("Le prix de la photo doit être défini");
        }
        else
        {
            $this->prix = $prix;
        }
    }

    public function prix():float
    {
        return $this->prix;
    }
}

