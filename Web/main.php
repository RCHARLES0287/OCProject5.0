<?php

const DEFAULT_APP = 'Frontend';

try
{
    require_once __DIR__ . "/../lib/vendor/autoload.php";

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
    $message = 'EXCEPTION ['.get_class($exception).'] : '.$exception->getMessage().' ('.$exception->getFile().':'.$exception->getLine().')';
    file_put_contents(__DIR__.'/../cache/erreur_'.date('Y-m-d').'.log', '['.date('H:i:s').'] '.$message.PHP_EOL, FILE_APPEND);
}

