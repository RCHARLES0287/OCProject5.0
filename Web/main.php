<?php
require_once __DIR__ . "/../lib/vendor/autoload.php";



//\App\Test::affiche();

require __DIR__.'/lib/vendor/autoload.php';
$loader = new \Twig\Loader\FilesystemLoader('/App/View');
$twig = new \Twig\Environment($loader, [
    'cache' => false
]);

echo $twig->render('general_layout.twig');