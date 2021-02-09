<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class UtilisateurEntity extends Entity
{
    protected const CLASS_PREFIX = 'utilisateurs';
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

    public function setEmail($email)
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


    public function setPassword($password)
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


    public function setBirthdate($birthdate)
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


    public function setPrenom($prenom)
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


    public function setNom($nom)
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


    public function setNumero_rue($numero_rue)
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


    public function setNom_rue($nom_rue)
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


    public function setComplement_adresse($complement_adresse)
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


    public function setCode_postal($code_postal)
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


    public function setVille($ville)
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


    public function setPays($pays)
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


    public function setTelephone($telephone)
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
