<?php


namespace Model;


use RCFramework\Manager;

class LignesDeCommandesManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getAllLignesDeCommandes()
    {
        $answerLignesCommandes = $this->db->prepare();
    }
}