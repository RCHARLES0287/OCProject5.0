<?php


namespace RCFramework;


use RCFramework\Entity;
use RCFramework\Utilitaires;


class EntityAuthentificationUser extends Entity
{
    protected const CLASS_PREFIX = 'utilisateurs';
    private string $email;
    private string $password;


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

}
