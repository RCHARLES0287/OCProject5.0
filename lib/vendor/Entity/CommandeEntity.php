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
    private string $date_facturation;


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


    public function setNom_et_prenom_utilisateur_parametres_separes($nom, $prenom)
    {
        if (Utilitaires::emptyMinusZero($nom) ||
            Utilitaires::emptyMinusZero($prenom))
        {
            throw new \Exception("Le nom et le prénom de l'utilisateur doivent être renseignés");
        }
        else
        {
            $this->setNom_et_prenom_utilisateur( $nom . '/' . $prenom);
        }
    }

    protected function setNom_et_prenom_utilisateur ($combinaisonNomPrenom)
    {
        if (Utilitaires::emptyMinusZero($combinaisonNomPrenom))
        {
            throw new \Exception("Le nom et le prénom de l'utilisateur doivent être renseignés");
        }
        $this->nom_et_prenom_utilisateur = $combinaisonNomPrenom;
    }

    public function nom_et_prenom_utilisateur():string
    {
        return $this->nom_et_prenom_utilisateur;
    }


    /**
     * Définit l'adresse de l'utilisateur
     *
     * @param string $numero_rue
     * @param string $nom_rue
     * @param string|null $complement_adresse
     * @param string|null $code_postal
     * @param string $ville
     * @param string $pays
     * @throws \Exception
     */
    public function setAdresse_utilisateur_parametres_separes($numero_rue, $nom_rue, $complement_adresse, $code_postal, $ville, $pays)
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
            $this->adresse_utilisateur = $numero_rue . '/' . $nom_rue . '/' . $complement_adresse . '/' . $code_postal . '/' . $ville . '/' . $pays;
        }
    }

    /**
     * @return array{numero_rue:string, nom_rue:string, complement_adresse:string|null, code_postal:string|null, ville:string, pays:string}
     */
    public function adresse_utilisateur_parametres_separes()
    {
        $adresseTableauRaw = explode('/', $this->adresse_utilisateur);
        return ['numero_rue'=>$adresseTableauRaw[0],
            'nom_rue'=>$adresseTableauRaw[1],
            'complement_adresse'=>$adresseTableauRaw[2],
            'code_postal'=>$adresseTableauRaw[3],
            'ville'=>$adresseTableauRaw[4],
            'pays'=>$adresseTableauRaw[5]];
    }

    protected function setAdresse_utilisateur ($combinaisonNumerorueNomrueCodepostalVillePays)
    {
        if (Utilitaires::emptyMinusZero($combinaisonNumerorueNomrueCodepostalVillePays))
        {
            throw new \Exception("L'adresse de l'utilisateur doit être entièrement renseignée");
        }
        $this->adresse_utilisateur = $combinaisonNumerorueNomrueCodepostalVillePays;
    }


    public function adresse_utilisateur():string
    {
        return $this->adresse_utilisateur;
    }


    public function setDatefacturation($date_facturation)
    {
        $this->date_facturation = $date_facturation;
    }

    public function date_facturation():string
    {
        return $this->date_facturation;
    }
}

