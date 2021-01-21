<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class UtilisateurEntity extends Entity
{
    private string $email;
    private string $password;
    private ?string $birthdate;
    private ?string $prenom;
    private ?string $nom;
    private ?string $numero_rue;
    private ?string $nom_rue;
    private ?string $complement_adresse;
    private ?string $code_postal;
    private ?string $ville;
    private ?string $pays;
    private ?string $telephone;

    public function setEmail(string $email)
    {
        if (Utilitaires::emptyMinusZero($email))
        {
            throw new \Exception("Un email doit être renseigné");
        }
        else
        {
            $this->email = $email;
        }
    }

    public function email():string
    {
        return $this->email;
    }


    public function setPassword(string $password)
    {
        if (Utilitaires::emptyMinusZero($password))
        {
            throw new \Exception("Un mot de passe doit être défini");
        }
        else
        {
            $this->password = $password;
        }
    }

    public function password():string
    {
        return $this->password;
    }


    public function setBirthdate(?string $birthdate)
    {
        if (Utilitaires::emptyMinusZero($birthdate))
        {
            $this->birthdate = null;
        }
    }

    public function birthdate():?string
    {
        return $this->birthdate;
    }


    public function setPrenom(?string $prenom)
    {
        if (Utilitaires::emptyMinusZero($prenom))
        {
            $this->prenom = null;
        }
    }

    public function prenom():?string
    {
        return $this->prenom;
    }


    public function setNom(?string $nom)
    {
        if (Utilitaires::emptyMinusZero($nom))
        {
            $this->nom = null;
        }
    }

    public function nom():?string
    {
        return $this->nom;
    }


    public function setNumero_rue(?string $numero_rue)
    {
        if (Utilitaires::emptyMinusZero($numero_rue))
        {
            $this->numero_rue =null;
        }
    }

    public function numero_rue():?string
    {
        return $this->numero_rue;
    }


    public function setNom_rue(?string $nom_rue)
    {
        if (Utilitaires::emptyMinusZero($nom_rue))
        {
            $this->nom_rue = null;
        }
    }

    public function nom_rue():?string
    {
        return $this->nom_rue;
    }


    public function setComplement_adresse(?string $complement_adresse)
    {
        if (Utilitaires::emptyMinusZero($complement_adresse))
        {
            $this->complement_adresse = null;
        }
    }

    public function complement_adresse():?string
    {
        return $this->complement_adresse;
    }


    public function setCode_postal(?string $code_postal)
    {
        if (Utilitaires::emptyMinusZero($code_postal))
        {
            $this->code_postal = null;
        }
    }

    public function code_postal():?string
    {
        return $this->code_postal;
    }


    public function setVille(?string $ville)
    {
        if (Utilitaires::emptyMinusZero($ville))
        {
            $this->ville = null;
        }
    }

    public function ville():?string
    {
        return $this->ville;
    }


    public function setPays(?string $pays)
    {
        if (Utilitaires::emptyMinusZero($pays))
        {
            $this->pays = null;
        }
    }

    public function pays():?string
    {
        return $this->pays;
    }


    public function setTelephone(?string $telephone)
    {
        if (Utilitaires::emptyMinusZero($telephone))
        {
            $this->telephone = null;
        }
    }

    public function telephone():?string
    {
        return $this->telephone;
    }
}
