<?php
try
{
    require_once __DIR__ . "/../lib/vendor/autoload.php";



//\App\Test::affiche();

    $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../App');
    $twig = new \Twig\Environment($loader, [
        'cache' => false
    ]);

    echo $twig->render('Templates/general_layout.html.twig');
    echo 'tototototo';
}
catch (Throwable $exception)
{
    echo 'ERREUR : Merci de contacter le responsable du site';
    ob_start();
    var_dump($exception);
    $vardumpcontent = ob_get_clean();
    file_put_contents(__DIR__.'/../cache/erreur_'.date('Y-m-d').'.log', '['.date('H:i:s').'] '.$vardumpcontent, FILE_APPEND);
}
