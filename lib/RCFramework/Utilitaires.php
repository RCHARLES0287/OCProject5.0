<?php


namespace RCFramework;


use http\Exception\RuntimeException;

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


    public static function deletedirectory($dir)
    {

        foreach (scandir($dir) as $sousElement)
        {
            /*self::var_dump($sousElement);
            exit;*/

            if (in_array($sousElement, ['.', '..']))
            {
                continue;
            }

            $sousElement = $dir . DIRECTORY_SEPARATOR . $sousElement;

            if (is_dir($sousElement))
            {
                self::deletedirectory($sousElement);
            }
            else
            {
                if (unlink($sousElement) === false)
                {
                    throw new RuntimeException("Echec de la suppression du fichier " . $sousElement . " : " . error_get_last()["message"]);
                }
            }
        }
        if (rmdir($dir) === false)
        {
            throw new RuntimeException("Echec de la suppression du répertoire " . $dir . " : " . error_get_last()["message"]);
        }
    }

//    Versions alternatives de la fonction deletedirectory
    /*
    $directoryIterator = new RecursiveDirectoryIterator(
        $repertoireASupprimer,
        FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS
    );
    $mainIterator = new RecursiveIteratorIterator(
        $directoryIterator,
        RecursiveIteratorIterator::CHILD_FIRST
    );

/** @var SplFileInfo $sousElement */
    /*
    foreach ($mainIterator as $sousElement) {
        if ($sousElement->isDir()) {
            rmdir($sousElement->getPathname());
        }
        else {
            unlink($sousElement->getPathname());
        }
    }
    rmdir($repertoireASupprimer);
    */

    /*
    Version ultra compacte

    foreach (
        new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($repertoireASupprimer,FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        ) as $sousElement
    ) {
        ($sousElement->isDir() ? 'rmdir' : 'unlink')($sousElement->getPathname());
    }
    rmdir($repertoireASupprimer);
    */


    public static function remplacementMosaique($cheminImage, $serialNumber, $descriptifPhoto): string
    {
        return '<div class="bloc_photo">
                    <div class="photo_dans_galerie photo_dans_mosaique shadow-4-strong">
                        <a href="'. $cheminImage .'"
                               title="'. $serialNumber .'">
                            <img alt="description" src="'. $cheminImage .'">
                        </a>
                    </div>
                    <div class="descriptif_photo">'. $descriptifPhoto .'</div>
                </div>';
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
        $message .= "\ntrace : " . $exception->getTraceAsString();
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
