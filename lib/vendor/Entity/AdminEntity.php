<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class AdminEntity extends Entity
{
    protected const CLASS_PREFIX = 'admins';
    private string $login;
    private string $password;

    public function setLogin($login)
    {
        if (Utilitaires::emptyMinusZero($login))
        {
            throw new \Exception("Un login doit être défini");
        }
        else
        {
            $this->login = $login;
        }
    }

    public function login():string
    {
        return $this->login;
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

