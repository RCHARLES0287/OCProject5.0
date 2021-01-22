<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class PhotoEntity extends Entity
{
    private ?int $galerie_id;
    private string $serial_number;
    private ?string $name;
    private ?int $type_id;
    private ?string $lieu;
    private ?string $description;

    public function setGalerie_id(?int $galerie_id)
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


    public function setSerial_number(int $serial_number)
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

    public function serial_number():int
    {
        return $this->serial_number;
    }


    public function setName(?string $name)
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


    public function setType_id(?int $type_id)
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


    public function setLieu(?string $lieu)
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


    public function setDescription(?string $description)
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
}