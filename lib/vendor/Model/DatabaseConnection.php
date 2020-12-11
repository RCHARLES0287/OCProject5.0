<?php

namespace Model;

use Exception;
use PDO;

class DatabaseConnection
{
    static public function dbConnect()
    {
        try
        {
            $db = new PDO('mysql:host=romainchgcdemost.mysql.db;dbname=romainchgcdemost;charset=utf8', 'romainchgcdemost', 'aP0lLoI3H0A1P', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
        } catch (Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

        return $db;
    }
}

