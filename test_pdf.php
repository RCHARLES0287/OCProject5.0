<?php
//Le session_start() est placé ici car on va utiliser la session dans de nombreuses pages
use RCFramework\FacturePDF;


require_once __DIR__ . "/lib/vendor/autoload.php";
//Le require ci-dessous n'est pas nécessaire car on passe par Composer
//require_once __DIR__ . "/../vendor/fpdf/fpdf/original/fpdf.php";


error_reporting(E_ALL & ~E_NOTICE);

$nomClient = 'Bob Trucmuche';
$adresseClient = '31 rue de Rivoli 75004 Paris';
$complementInfoClient = '';
$numeroFacture = 71;
$entetesColonnes = ['Produit(s) et/ou Prestations', 'Prix unitaire', 'Quantité', 'Total'] ;
$tableauData = [['Photo mission Apollo', 50, 2, 100], ['Photo Space Shuttle', 30, 5, 150]];
//$tableauData = ['Photo mission Apollo', 50, 2, 100, 'Photo Space Shuttle', 30, 5, 150];
$fraisDePort = 250;
$total = 1350;


//$paragrapheLegal = "Dispensé d'immatriculation au registre du commerce et des sociétés (RCS) et au répertoire des métiers etc etc";
//$paragrapheLegal = "Dispensé d'immatriculation \n au registre du commerce et des sociétés (RCS) et au répertoire des métiers etc etc";

$date = '25/06/2021';

$cheminFacture = __DIR__ . '/facture_numero_'.$numeroFacture.'.pdf';

$newFacturePDF = new FacturePDF($nomClient, $adresseClient, $complementInfoClient, $date, $numeroFacture,
    $tableauData, $fraisDePort, $total, $cheminFacture);

/*$newFacturePDF->AddPage();
$newFacturePDF->complementEntete($nomClient, $adresseClient, $complementInfoClient);
$newFacturePDF->writeParagrapheLegal($paragrapheLegal);
$newFacturePDF->setDate($date);
$newFacturePDF->setNumeroFacture($numeroFacture);
$newFacturePDF->ajoutArticles($entetesColonnes, $tableauData);
$newFacturePDF->setFraisDePortEtTotal ($fraisDePort, $total);
$newFacturePDF->setDateEtModeReglement($date);
$newFacturePDF->Output('F', $cheminFacture);*/


