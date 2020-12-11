<?php


namespace RCFramework;


use Model\DatabaseConnection;

class Manager
{
    protected $db = null;

    public function __construct()
    {
        $this->db = DatabaseConnection::dbConnect();
    }
}
