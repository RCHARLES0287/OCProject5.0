<?php


namespace Model;


use Entity\AdminEntity;
use RCFramework\ManagerAuthentification;

class AdministrateurManager extends ManagerAuthentification
{
    protected function getTypeOfUser()
    {
        return AdminEntity::class;
    }

    public function getOneAdministrateurWithAdminId ($adminId)
    {
        $answerAdministrateurData = $this->db->prepare('SELECT * FROM rc_photographe_admins WHERE admins_id=:adminId');
        $answerAdministrateurData ->execute(array(
            'adminId' => $adminId
        ));

        $dbAdministrateur = $answerAdministrateurData->fetch();

        if ($dbAdministrateur === false)
        {
            throw new \Exception("Aucun administrateur ne porte l'Id " . $adminId);
        }
        else
        {
            return new AdminEntity($dbAdministrateur);
        }
    }

    public function getOneAdministrateurWithEmail ($email)
    {
        $answerAdministrateurData = $this->db->prepare('SELECT * FROM rc_photographe_admins WHERE admins_login=:email');
        $answerAdministrateurData ->execute(array(
            'emailPassword' => $email
        ));

        $dbAdministrateur = $answerAdministrateurData->fetch();

        if ($dbAdministrateur === false)
        {
            throw new \Exception("Aucun administrateur n'utilise l'email " . $email);
        }
        else
        {
            return new AdminEntity($dbAdministrateur);
        }
    }

}

