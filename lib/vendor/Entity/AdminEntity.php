<?php


namespace Entity;


use RCFramework\EntityAuthentificationUser;
use RCFramework\Utilitaires;

class AdminEntity extends EntityAuthentificationUser
{
    protected const CLASS_PREFIX = 'admins';

    private ?string $email_password = null;

    public function setEmail_password($emailPassword)
    {
        if (Utilitaires::emptyMinusZero($emailPassword))
        {
            throw new \Exception("Un mot de passe de messagerie doit être défini");
        }
        else
        {
            $this->email_password = $emailPassword;
        }
    }

    public function email_password():string
    {
        return $this->email_password;
    }


    /*private string $login;
    private string $password;*/

    /*public function setLogin($login)
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
    }*/
}

