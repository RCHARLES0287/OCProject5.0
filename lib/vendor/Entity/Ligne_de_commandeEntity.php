<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class Ligne_de_commandeEntity extends Entity
{
    private int $commande_id;
    private string $nom_prenom_adresse;
    private string $photo_serial_number;
    private string $photo_name;
    private string $dimensions;
    private float $tarif;
    private int $nombre_exemplaires;

    public function setCommande_id(int $commande_id)
    {
        if (Utilitaires::emptyMinusZero($commande_id))
        {
            throw new \Exception("L'id de la commande doit être défini");
        }
        else
        {
            $this->commande_id = $commande_id;
        }
    }

    public function commande_id():int
    {
        return $this->commande_id;
    }


    public function setNom_prenom_adresse(string $nom_prenom_adresse)
    {
        if (Utilitaires::emptyMinusZero($nom_prenom_adresse))
        {
            throw new \Exception('Les nom, prénom et adresse doivent être renseignés');
        }
        else
        {
            $this->nom_prenom_adresse = $nom_prenom_adresse;
        }
    }

    public function nom_prenom_adresse():string
    {
        return $this->nom_prenom_adresse;
    }


    public function setPhoto_serial_number(string $photo_serial_number)
    {
        if (Utilitaires::emptyMinusZero($photo_serial_number))
        {
            throw new \Exception('Le numéro de série de la photo doit être défini');
        }
        else
        {
            $this->photo_serial_number = $photo_serial_number;
        }
    }

    public function photo_serial_number():string
    {
        return $this->photo_serial_number;
    }


    public function setPhoto_name(string $photo_name)
    {
        if (Utilitaires::emptyMinusZero($photo_serial_number))
        {
            throw new \Exception('Le nom de la photo doit être défini');
        }
        else
        {
            $this->photo_name = $photo_name;
        }
    }

    public function photo_name():string
    {
        return $this->photo_name;
    }


    public function setDimensions(string $dimensions)
    {
        if (Utilitaires::emptyMinusZero($dimensions))
        {
            throw new \Exception('Les dimensions de la photo doivent être définies');
        }
        else
        {
            $this->dimensions = $dimensions;
        }
    }

    public function dimensions():string
    {
        return $this->dimensions;
    }


    public function setTarif(float $tarif)
    {
        if (Utilitaires::emptyMinusZero($tarif))
        {
            throw new \Exception('Le tarif doit être défini');
        }
        else
        {
            $this->tarif = $tarif;
        }
    }

    public function tarif():float
    {
        return $this->tarif;
    }


    public function setNombre_exemplaires(float $nombre_exemplaires)
    {
        if (Utilitaires::emptyMinusZero($nombre_exemplaires))
        {
            throw new \Exception("Le nombre d'exemplaires doit être défini");
        }
        else
        {
            $this->tarif = $nombre_exemplaires;
        }
    }

    public function nombre_exemplaires():float
    {
        return $this->nombre_exemplaires;
    }
}
