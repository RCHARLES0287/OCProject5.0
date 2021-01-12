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
}
