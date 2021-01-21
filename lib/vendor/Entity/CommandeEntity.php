<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class CommandeEntity extends Entity
{
    private string $numero_commande;
    private string $numero_facture;
    private float $montant_total;
    private ?int $id_utilisateur;
    private string $nom_et_prenom_utilisateur;
    private string $adresse_utilisateur;

    public function setNumero_commande(string $numero_commande)
    {
        if (Utilitaires::emptyMinusZero($numero_commande))
        {
            throw new \Exception('Le numéro de commande doit être renseigné');
        }
        else
        {
            $this->numero_commande = $numero_commande;
        }
    }

    public function numero_commande(): string
    {
        return $this->numero_commande;
    }


    public function setNumero_facture(string $numero_facture)
    {
        if (Utilitaires::emptyMinusZero($numero_facture))
        {
            throw new \Exception('Le numéro de facture doit être renseigné');
        }
        else
        {
            $this->numero_facture = $numero_facture;
        }
    }

    public function numero_facture():string
    {
        return $this->numero_facture;
    }


    public function setMontant_total(float $montant_total)
    {
        if (Utilitaires::emptyMinusZero($montant_total))
        {
            throw new \Exception('Le montant total doit être renseigné');
        }
        else
        {
            $this->montant_total = $montant_total;
        }
    }

    public function montant_total():float
    {
        return $this->montant_total;
    }


    public function setId_utilisateur(?int $id_utilisateur)
    {
        if (Utilitaires::emptyMinusZero($id_utilisateur))
        {
            $this->id_utilisateur = null;
        }
        else
        {
            $this->id_utilisateur = $id_utilisateur;
        }
    }

    public function id_utilisateur():?int
    {
        return $this->id_utilisateur;
    }


    public function setNom_et_prenom_utilisateur(string $nom_et_prenom_utilisateur)
    {
        if (Utilitaires::emptyMinusZero($nom_et_prenom_utilisateur))
        {
            throw new \Exception("Les nom et prénom de l'utilisateur doivent être renseignés");
        }
        else
        {
            $this->nom_et_prenom_utilisateur = $nom_et_prenom_utilisateur;
        }
    }

    public function nom_et_prenom_utilisateur():string
    {
        return $this->nom_et_prenom_utilisateur;
    }


    public function setAdresse_utilisateur(string $adresse_utilisateur)
    {
        if (Utilitaires::emptyMinusZero($adresse_utilisateur))
        {
            throw new \Exception("L'adresse de l'utilisateur doit être renseignée");
        }
        else
        {
            $this->adresse_utilisateur = $adresse_utilisateur;
        }
    }

    public function adresse_utilisateur():string
    {
        return $this->adresse_utilisateur;
    }
}

