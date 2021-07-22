<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class CommandeEntity extends Entity
{
    protected const CLASS_PREFIX = 'commandes';
    private string $numero_commande;
    private ?string $numero_facture = null;
    private float $montant_total;
    private ?int $id_utilisateur;
    private string $nom_et_prenom_utilisateur;
    private string $adresse_utilisateur;
    private int $validation_panier;

    public function setNumero_commande($numero_commande)
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

    public function setNumero_facture($numero_facture)
    {
        if (Utilitaires::emptyMinusZero($numero_facture))
        {
            $this->numero_facture = null;
        }
        else
        {
            $this->numero_facture = $numero_facture;
        }
    }

    public function numero_facture():?string
    {
        return $this->numero_facture;
    }


    public function setMontant_total($montant_total)
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


    public function setId_utilisateur($id_utilisateur)
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


    public function setNom_et_prenom_utilisateur($nom, $prenom)
    {
        if (Utilitaires::emptyMinusZero($nom) ||
            Utilitaires::emptyMinusZero($prenom))
        {
            var_dump($nom, $prenom);
            throw new \Exception("Les nom et prénom de l'utilisateur doivent être renseignés");
        }
        else
        {
            $this->nom_et_prenom_utilisateur = $nom . '/' . $prenom;
        }
    }

    public function nom_et_prenom_utilisateur():string
    {
        return $this->nom_et_prenom_utilisateur;
    }


    public function setAdresse_utilisateur($numero_rue, $nom_rue, $code_postal, $ville, $pays)
    {
        if (Utilitaires::emptyMinusZero($numero_rue) ||
            Utilitaires::emptyMinusZero($nom_rue) ||
            Utilitaires::emptyMinusZero($code_postal) ||
            Utilitaires::emptyMinusZero($ville) ||
            Utilitaires::emptyMinusZero($pays))
        {
            throw new \Exception("L'adresse de l'utilisateur doit être entièrement renseignée");
        }
        else
        {
            $this->adresse_utilisateur = $numero_rue . '/' . $nom_rue . '/' . $code_postal . '/' . $ville . '/' . $pays;
        }
    }

    public function adresse_utilisateur():string
    {
        return $this->adresse_utilisateur;
    }


    public function setValidation_panier($validation_panier)
    {
        $this->validation_panier = $validation_panier;
    }

    public function validation_panier():int
    {
        return $this->validation_panier;
    }
}

