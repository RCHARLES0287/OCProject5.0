<?php


namespace Entity;

use Exception;
use RCFramework\Entity;
use RCFramework\Utilitaires;

class GalerieEntity extends Entity
{
    protected const CLASS_PREFIX = 'galeries';
    private ?string $nom_galerie = null;
    private ?int $ordre_affichage = null;
    private ?string $chemin_miniature = null;


    public function setNom_galerie($nom_galerie)
    {
        if (Utilitaires::emptyMinusZero($nom_galerie))
        {
            throw  new Exception('Un nom de galerie doit être renseigné');
        }
        else
        {
            $this->nom_galerie = $nom_galerie;
        }
    }

    public function nom_galerie(): ?string
    {
        return $this->nom_galerie;
    }

    /**
     * @param string|int $ordre_affichage
     * @throws Exception
     */
    public function setOrdre_affichage($ordre_affichage)
    {
        if (Utilitaires::emptyMinusZero($ordre_affichage))
        {
            $this->ordre_affichage = null;
        }
        else
        {
            $this->ordre_affichage = $ordre_affichage;
        }
    }

    /**
     * @return mixed
     */
    public function ordre_affichage():?int
    {
        return $this->ordre_affichage;
    }


    public function setChemin_miniature($chemin_miniature)
    {
        $this->chemin_miniature = $chemin_miniature;
    }

    public function chemin_miniature():?string
    {
        return $this->chemin_miniature;
    }
}
