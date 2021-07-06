<?php


namespace Entity;


use RCFramework\EntityAuthentificationUser;
use RCFramework\Utilitaires;

class UtilisateurEntity extends EntityAuthentificationUser
{
//    protected const CLASS_PREFIX = 'utilisateurs';
    /*private string $email;
    private string $password;*/
    private ?string $birthdate = null;
    private ?string $prenom = null;
    private ?string $nom = null;
    private ?string $numero_rue = null;
    private ?string $nom_rue = null;
    private ?string $complement_adresse = null;
    private ?string $code_postal = null;
    private ?string $ville = null;
    private ?string $pays = null;
    private ?string $telephone = null;

    /*public function setEmail($email)
    {
        if (Utilitaires::emptyMinusZero($email))
        {
            throw new \Exception("Un email doit être renseigné");
        }
        else
        {
            $this->email = $email;
        }
    }*/

    /*public function email():string
    {
        return $this->email;
    }*/


    /*public function setPassword($password)
    {
        if (Utilitaires::emptyMinusZero($password))
        {
            throw new \Exception("Un mot de passe doit être défini");
        }
        else
        {
            $this->password = $password;
        }
    }*/

    /*public function password():string
    {
        return $this->password;
    }*/


    public function setBirthdate($birthdate)
    {
        if (Utilitaires::emptyMinusZero($birthdate))
        {
            $this->birthdate = null;
        }
        else
        {
            $this->birthdate = $birthdate;
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
        else
        {
            $this->prenom = $prenom;
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
        else
        {
            $this->nom = $nom;
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
            $this->numero_rue = null;
        }
        else
        {
            $this->numero_rue = $numero_rue;
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
        else
        {
            $this->nom_rue = $nom_rue;
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
        else
        {
            $this->complement_adresse = $complement_adresse;
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
        else
        {
            $this->code_postal = $code_postal;
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
        else
        {
            $this->ville = $ville;
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
        else
        {
            $this->pays = $pays;
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
        else
        {
            $this->telephone = $telephone;
        }
    }

    public function telephone():?string
    {
        return $this->telephone;
    }
}
