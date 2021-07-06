<?php


namespace RCFramework;


use Entity\CommandeEntity;
use RCFramework\Manager;

class ManagerAuthentification extends Manager
{
    public function compareIdentificationWithDb($login, $password, $requeteSQL, $correspondanceLoginDB)
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

            $userFeatures = new EntityAuthentificationUser($dbUser);

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