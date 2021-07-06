<?php


namespace RCFramework;


abstract class Utilitaires
{
    public static function emptyMinusZero($value)
    {
        if ($value == '')       //Contrôle les chaînes vides
        {
            return true;
        }
        else
        {
            if (is_string($value) && trim($value) === '')       //Contrôle les chaînes d'espaces blancs
            {
                return true;
            }
            else
            {
                if ($value === null)
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
    }

    public static function var_dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    public static function logException($exception)
    {
        $message = 'EXCEPTION [' . get_class($exception) . '] : ' . $exception->getMessage() . ' (' . $exception->getFile() . ':' . $exception->getLine() . ')';
        static::logMessage($message);
    }

    public static function logMessage($message)
    {
        file_put_contents(__DIR__ . '/../../cache/erreur_' . date('Y-m-d') . '.log', '[' . date('H:i:s') . '] ' . $message . PHP_EOL, FILE_APPEND);
    }

    public static function returnJsonAndExit($value)
    {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($value, JSON_THROW_ON_ERROR);
        exit;
    }
}
