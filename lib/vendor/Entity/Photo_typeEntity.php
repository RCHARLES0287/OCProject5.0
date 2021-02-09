<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class Photo_typeEntity extends Entity
{
    protected const CLASS_PREFIX = 'photo_type';
    private string $type;

    public function setType($type)
    {
        if (Utilitaires::emptyMinusZero($type))
        {
            throw new \Exception("Le type de photo doit être défini");
        }
        else
        {
            $this->type = $type;
        }
    }

    public function type():string
    {
        return $this->type;
    }
}

