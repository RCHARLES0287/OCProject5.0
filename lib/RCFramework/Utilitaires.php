<?php


namespace RCFramework;


abstract class Utilitaires
{
    const EMAIL_VENDEUR_TEST = 'romain.charles@rocketmail.com';
    const EMAIL_VENDEUR = 'sb-ahide6955388@business.example.com';
    ///Todo Repasser sur la vraie adresse mail du vendeur
    const FRAIS_DE_PORT = 250;
    const NOMBRE_PHOTOS_PAR_PAGE_GALERIES = 6;


    public static function emptyMinusZero($value)
    {
        if ($value === '')       //Contrôle les chaînes vides
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

/*
    public static function deletedirectory ($dir)
    {
        foreach (scandir($dir) as $sousElement)
        {
            if (is_dir($sousElement))
            {
                Utilitaires::deletedirectory($sousElement);
            }
            else
            {
                unlink($sousElement);
            }
        }
        return rmdir($dir);
    }
*/


    public static function var_dump($var)
    {
        echo '<pre>';
        var_dump($var);
        echo '</pre>';
    }

    public static function logException($exception)
    {
        $message = 'EXCEPTION [' . get_class($exception) . '] : ' . $exception->getMessage() . ' (' . $exception->getFile() . ':' . $exception->getLine() . ')';
        $message.= "\ntrace : ". $exception->getTraceAsString();
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
