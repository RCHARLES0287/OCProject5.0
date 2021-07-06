<?php


namespace Model;

use Entity\PhotoEntity;
use Entity\UtilisateurEntity;
use PDO;
use RCFramework\ManagerAuthentification;
use RCFramework\Utilitaires;

class UtilisateursManager extends ManagerAuthentification
{
    protected function getTypeOfUser()
    {
        return UtilisateurEntity::class;
    }

    public function getOneUtilisateur($utilisateurId)
    {
        $answerUtilisateurData = $this->db->prepare('SELECT * FROM rc_photographe_utilisateurs WHERE utilisateurs_id=:utilisateurId');
        $answerUtilisateurData->execute(array(
            'utilisateurId' => $utilisateurId
        ));

        $dbUtilisateur = $answerUtilisateurData->fetch();

        if ($dbUtilisateur === false)
        {
            throw new \Exception("Aucun utilisateur ne porte l'Id " . $utilisateurId);
        }
        else
        {
            return new PhotoEntity($dbUtilisateur);
        }
    }


    public function saveOneUtilisateur(UtilisateurEntity $newUtilisateurEntity)
    {
        $testUtilisateurExist = $this->checkEmailUtilisateur($newUtilisateurEntity);
        if ($testUtilisateurExist === true)
        {
            $req = $this->db->prepare('INSERT INTO rc_photographe_utilisateurs(utilisateurs_email, utilisateurs_password, utilisateurs_birthdate, utilisateurs_prenom, utilisateurs_nom, utilisateurs_numero_rue, utilisateurs_nom_rue, utilisateurs_complement_adresse, utilisateurs_code_postal, utilisateurs_ville, utilisateurs_pays, utilisateurs_telephone)
                                                VALUES(:email, :password, :birthdate, :prenom, :nom, :numeroRue, :nomRue, :complementAdresse, :codePostal, :ville, :pays, :telephone)');
            $req->execute(array(
                'email' => $newUtilisateurEntity->email(),
                'password' => $newUtilisateurEntity->password(),
                'birthdate' => $newUtilisateurEntity->birthdate(),
                'prenom' => $newUtilisateurEntity->prenom(),
                'nom' => $newUtilisateurEntity->nom(),
                'numeroRue' => $newUtilisateurEntity->numero_rue(),
                'nomRue' => $newUtilisateurEntity->nom_rue(),
                'complementAdresse' => $newUtilisateurEntity->complement_adresse(),
                'codePostal' => $newUtilisateurEntity->code_postal(),
                'ville' => $newUtilisateurEntity->ville(),
                'pays' => $newUtilisateurEntity->pays(),
                'telephone' => $newUtilisateurEntity->telephone()
            ));
        }
        else
        {
            throw new \Exception("Un utilisateur porte déjà cet identifiant");
        }
    }


    public function checkEmailUtilisateur(UtilisateurEntity $utilisateurEntity)
    {

        $dbEmail = $this->db->prepare("SELECT utilisateurs_email FROM rc_photographe_utilisateurs WHERE utilisateurs_email=:utilisateurEmail");

        $dbEmail->bindValue('utilisateurEmail', $utilisateurEntity->email(), \PDO::PARAM_STR);
        $dbEmail->execute();

        $emailTest = $dbEmail->fetch(PDO::FETCH_COLUMN);
//        Le test se fait avec un "triple =" pour vérifier à la fois la valeur ET le type afin de ne pas matcher avec la valeur 0
        if ($emailTest === false)
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    /*public function compareVisitorWithDb($visitorLogin, $visitorPwd)
    {
        if (!Utilitaires::emptyMinusZero($visitorLogin) && !Utilitaires::emptyMinusZero($visitorPwd))
        {
            $answerUtilisateurData = $this->db->prepare('SELECT * FROM rc_photographe_utilisateurs WHERE utilisateurs_email=:utilisateurEmail');
            $answerUtilisateurData->execute(array('utilisateurEmail' => $visitorLogin));
            $dbUtilisateur = $answerUtilisateurData->fetch();

            if ($dbUtilisateur === false)
            {
                return null;
            }

            $utilisateurFeatures = new UtilisateurEntity($dbUtilisateur);

            if (password_verify($visitorPwd, $utilisateurFeatures->password()))
            {
                return $utilisateurFeatures;
            }
            else
            {
                return null;
            }

        }
        return null;
    }*/

}

