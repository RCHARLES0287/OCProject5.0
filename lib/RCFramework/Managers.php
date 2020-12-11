<?php


namespace RCFramework;


use Model\DatabaseConnection;

class Managers
{
    protected $db = null;

    public function __construct()
    {
        $this->db = DatabaseConnection::dbConnect();
    }
}