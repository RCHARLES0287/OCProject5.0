<?php


namespace Entity;

use RCFramework\Entity;
use RCFramework\Utilitaires;

class GalerieEntity extends Entity
{
    private string $nom_galerie;
    private $ordre_affichage;
    private $chemin_miniature;


    public function setNom_galerie(string $nom_galerie)
    {
        if (Utilitaires::emptyMinusZero($nom_galerie))
        {
            throw  new \Exception('Un nom de galerie doit être renseigné');
        }
        else
        {
            $this->nom_galerie = $nom_galerie;
        }
    }

    public function nom_galerie(): string
    {
        return $this->nom_galerie;
    }

    /**
     * @param string|int $ordre_affichage
     * @throws \Exception
     */
    public function setOrdre_affichage($ordre_affichage)
    {
        if (Utilitaires::emptyMinusZero($ordre_affichage))
        {
            throw new \Exception("L'ordre de la galerie doit être défini");
        }
        else
        {
            $this->ordre_affichage = $ordre_affichage;
        }
    }

    /**
     * @return mixed
     */
    public function ordre_affichage()
    {
        return $this->ordre_affichage;
    }


    public function setChemin_miniature($chemin_miniature)
    {
        $this->chemin_miniature = $chemin_miniature;
    }

    public function chemin_miniature()
    {
        return $this->chemin_miniature;
    }
}