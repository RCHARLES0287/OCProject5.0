<?php
//Le session_start() est placé ici car on va utiliser la session dans de nombreuses pages
use RCFramework\Utilitaires;

//const FPDF_FONTPATH = __DIR__ . '/../lib/vendor/setasign/tfpdf/font/';

require_once __DIR__ . "/../lib/vendor/autoload.php";
//Le require ci-dessous n'est pas nécessaire car on passe par Composer
//require_once __DIR__ . "/../vendor/fpdf/fpdf/original/fpdf.php";

session_start();

const DEFAULT_APP = 'Frontend';

error_reporting(E_ALL & ~E_NOTICE);

function ultimateErrorHandler ($severity, $message, $file, $line)
{
    if (!(error_reporting() & $severity))
    {
        return;
    }
    Utilitaires::logMessage("Erreur inattendue. Fichier : " . $file . ". Ligne : " . $line . ". Sévérité : " . $severity . ". Message : " . $message);
    throw new ErrorException($message, 0, $severity, $file, $line);
}
set_error_handler('ultimateErrorHandler');

try
{

// Si l'application n'est pas valide, on va charger l'application par défaut qui se chargera de générer une erreur 404
    if (!isset($_GET['app']) || !file_exists(__DIR__.'/../App/'.$_GET['app']))
    {
        $_GET['app'] = DEFAULT_APP;
    }


    // Il ne nous suffit plus qu'à déduire le nom de la classe et à l'instancier
    $appClass = 'App\\'.$_GET['app'].'\\'.$_GET['app'].'Application';

    $app = new $appClass;

    $app->run();

//\App\Test::affiche();
/*
    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../App');
    $twig = new \Twig\Environment($loader, [
        'cache' => false
    ]);

    echo $twig->render('Templates/general_layout.html.twig');
    echo 'tototototo';*/
}
catch (Throwable $exception)
{
    echo 'ERREUR : Merci de contacter le responsable du site';
    Utilitaires::logException($exception);
}

