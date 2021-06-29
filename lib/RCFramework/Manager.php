<?php


namespace RCFramework;


use InvalidArgumentException;
use Model\DatabaseConnection;

class Manager
{
    protected $db = null;
    protected $managers = [];

    public function __construct()
    {
        $this->db = DatabaseConnection::dbConnect();
    }

    public function getManagerOf($module)
    {
        if (!is_string($module) || Utilitaires::emptyMinusZero($module))
        {
            throw new InvalidArgumentException('Le module spécifié est invalide');
        }

        if (!isset($this->managers[$module]))
        {
            $manager = '\\Model\\' . $module . 'Manager';

            $this->managers[$module] = new $manager($this->db);
        }

        return $this->managers[$module];
    }



    public function compareIdentificationWithDb($login, $password, $requeteSQL, $correspondanceLoginDB, $userEntity)
    {
        if (!Utilitaires::emptyMinusZero($login) && !Utilitaires::emptyMinusZero($password))
        {
            $answerData = $this->db->prepare($requeteSQL);
            $answerData->execute(array($correspondanceLoginDB => $login));
            $dbUser = $answerData->fetch();

            if ($dbUser === false)
            {
                return null;
            }

            $userFeatures = new $userEntity($dbUser);

            if (password_verify($password, $userFeatures->password()))
            {
                return $userFeatures;
            }
            else
            {
                return null;
            }

        }
        return null;
    }

    /*
    public function compareIdentificationWithDb($login, $password)
    {
        if (!Utilitaires::emptyMinusZero($login) && !Utilitaires::emptyMinusZero($password))
        {
            $answerUtilisateurData = $this->db->prepare('SELECT * FROM rc_photographe_utilisateurs WHERE utilisateurs_email=:utilisateurEmail');
            $answerUtilisateurData->execute(array('utilisateurEmail' => $login));
            $dbUtilisateur = $answerUtilisateurData->fetch();

            if ($dbUtilisateur === false)
            {
                return null;
            }

            $utilisateurFeatures = new UtilisateurEntity($dbUtilisateur);

            if (password_verify($password, $utilisateurFeatures->password()))
            {
                return $utilisateurFeatures;
            }
            else
            {
                return null;
            }

        }
        return null;
    }
    */

}
