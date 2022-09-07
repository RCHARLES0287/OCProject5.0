<?php


namespace RCFramework;


use Entity\CommandeEntity;
use RCFramework\Manager;

abstract class ManagerAuthentification extends Manager
{
    protected abstract function getTypeOfUser ();

    public function compareIdentificationWithDb($login, $password, $requeteSQL, $correspondanceLoginDB)
    {
        /*var_dump($login, $password, $requeteSQL, $correspondanceLoginDB);
        exit;*/

        if (!Utilitaires::emptyMinusZero($login) && !Utilitaires::emptyMinusZero($password))
        {
            $answerData = $this->db->prepare($requeteSQL);
            $answerData->execute(array($correspondanceLoginDB => $login));
            $dbUser = $answerData->fetch();

            /*var_dump($dbUser);
            exit;*/

            if ($dbUser === false)
            {
                return null;
            }

            $typeOfUser = $this->getTypeOfUser();

            /*var_dump($typeOfUser);
            exit;*/

            $userFeatures = new $typeOfUser($dbUser);
            /*var_dump($userFeatures);
            exit;*/

            /*var_dump($password, password_verify($password, $userFeatures->password()));
            exit;*/


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