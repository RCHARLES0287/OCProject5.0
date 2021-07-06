<?php


namespace Model;


use Entity\AdminEntity;
use RCFramework\ManagerAuthentification;

class AdministrateurManager extends ManagerAuthentification
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getOneAdministrateur ($adminId)
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
}

