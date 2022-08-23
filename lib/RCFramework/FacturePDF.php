<?php

namespace RCFramework;

use DateTime;
use Entity\CommandeEntity;
use Model\LignesDeCommandesManager;
use tFPDF;



class FacturePDF extends tFPDF
{
    private float $y0bloc;  // Ordonnée du début du bloc
    const PAYPAL = 'Paypal';


    public function __construct($entiteCommande, $cheminFacture)
    {
        parent::__construct();
        $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);

        $this->AddPage();
        $this->complementEntete($entiteCommande);
        $this->writeParagrapheLegal();
        $this->setDateEntete($entiteCommande);
        $this->setNumeroFacture($entiteCommande);
        $this->ajoutArticles($entiteCommande);
        $this->setFraisDePortEtTotal ($entiteCommande);
        $this->setDateEtModeReglement($entiteCommande);
        $this->Output('F', $cheminFacture);
    }


    public function header ()
    {
        $entetePrincipale = 'ROMAIN CHARLES PHOTOGRAPHE';
        $this->SetFont('DejaVu','B',16);
        // Calcul de la largeur du titre et positionnement
        $w = $this->GetStringWidth($entetePrincipale) + 6;
        $this->SetX(($this->GetPageWidth()-$w)/2);
        $this->Cell(100, 15, $entetePrincipale, 0,0,'C');
        $this->Ln(10);

    }

    public function footer ()
    {
        $texteFooter = 'Romain CHARLES - SIREN 793 510 942 - 102 Avenue Marie Mauron 13320 Bouc Bel Air';
        $this->SetY($this->GetPageHeight()-25);
        $this->SetFont('DejaVu','',10);
        // Calcul de la largeur du titre et positionnement
        $w = $this->GetStringWidth($texteFooter);
        $this->SetX(($this->GetPageWidth()/2)-($w/2));
        $this->Cell($w, 15, $texteFooter, 0,0,'C');
//        $this->Ln(10);
    }

    /**
     * @param CommandeEntity $entiteCommande
     */
    private function complementEntete (CommandeEntity $entiteCommande)
    {
        $entete = 'Facture';
        $w = $this->GetStringWidth($entete);
        $this->SetFont('DejaVu','',10);
        $this->SetX(($this->GetPageWidth()/2)-($w/2));
        $this->Cell($w, 12, $entete, 0,0,'C');
        $this->Ln(10);

        $this->y0bloc = $this->GetY();
        $enteteVendeur = "Romain CHARLES\nPhotographe\nNuméro SIREN : 793 510 942\n102 Avenue Marie Mauron 13320 Bouc Bel Air";
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);

        $this->MultiCell(80, 5, $enteteVendeur, 1, 'C', false);

        /*$this->y0bloc = $this->GetY();
        $enteteVendeur = 'Romain CHARLES Photographe';
        $enteteVendeur2 = 'Numéro SIREN : 793 510 942';
        $enteteVendeur3 = '102 Avenue Marie Mauron 13320 Bouc Bel Air';
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
//        $this->MultiCell($largeurEnteteVendeurEtClient, 5, $enteteVendeur, 1,0,'');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteVendeur, 0, 1, 'C');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteVendeur2, 0, 1, 'C');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteVendeur3, 0, 1, 'C');*/


        $this->SetY($this->y0bloc);
        $this->SetCol(1);
        $adresseClient = $entiteCommande->adresse_utilisateur_parametres_separes();
        $enteteClient = $entiteCommande->nom_et_prenom_utilisateur() . "\n" . $adresseClient['numero_rue'] . ' ' . $adresseClient['nom_rue'] . (Utilitaires::emptyMinusZero($adresseClient['complement_adresse'])?'':"\n" . $adresseClient['complement_adresse']) . "\n" . $adresseClient['code_postal'] . ' ' . $adresseClient['ville'] . "\n" . $adresseClient['pays'];
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);

        $largeurEnteteVendeurEtClient = max($this->GetStringWidth($entiteCommande->nom_et_prenom_utilisateur()),
            $this->GetStringWidth($adresseClient['numero_rue'] . ' ' . $adresseClient['nom_rue']),
            $this->GetStringWidth($adresseClient['complement_adresse']),
        $this->GetStringWidth($adresseClient['code_postal'] . ' ' . $adresseClient['ville']),
        $this->GetStringWidth($adresseClient['pays']));

        $this->SetX(200-$largeurEnteteVendeurEtClient);
//        $this->MultiCell($largeurEnteteVendeurEtClient, 5, $enteteClient, 1,0,'');
        $this->MultiCell($largeurEnteteVendeurEtClient, 5, $enteteClient, 1, 'C', false);
        $this->Ln(10);

        /*$this->SetY($this->y0bloc);
        $this->SetCol(1);
        $enteteClient = $nomClient;
        $enteteClient2 = $adresseClient;
        $enteteClient3 = $complementInfoClient;
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $largeurEnteteVendeurEtClient = ($this->GetPageWidth()/2)-20;
//        $this->MultiCell($largeurEnteteVendeurEtClient, 5, $enteteClient, 1,0,'');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteClient, 0, 1, 'C');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteClient2, 0, 1, 'C');
        $this->Cell($largeurEnteteVendeurEtClient, 5, $enteteClient3, 0, 1, 'C');
        $this->Ln(10);*/
    }

    private function SetCol($col)
    {
        // Positionnement sur une colonne
        $x = 10+$col*($this->GetPageWidth()/2);
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    private function writeParagrapheLegal ()
    {
        $this->y0bloc = $this->GetY();
        $this->SetX(10);
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->MultiCell($this->GetPageWidth()/3, 5,
            'Dispensé d\'immatriculation au registre du commerce et des sociétés (RCS) et au répertoire des métiers',
            0,'J','');
    }


    /**
     * @param CommandeEntity $entiteCommande
     * @return string
     */
    private function setDate (CommandeEntity $entiteCommande)
    {
        try
        {
            $dateFacturation = new DateTime($entiteCommande->date_facturation());
        }
        catch (\Exception $exception)
        {
//            Par défaut DateTime() renvoie la date/heure actuelle (du serveur)
            $dateFacturation = new DateTime();
            Utilitaires::logMessage('Une exception a été levée sur la date de facturation : ' . $exception->getMessage() . ', la date qui génère l\'erreur est ' . $entiteCommande->date_facturation());
        }

        return $dateFacturation->format("d/m/Y");
    }


    /**
     * @param CommandeEntity $commandeEntity
     */
    private function setDateEntete (CommandeEntity $commandeEntity)
    {
        $dateFacturation = $this->SetDate($commandeEntity);
        $this->SetCol(1);
        $this->SetY($this->y0bloc+5);
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->Cell(100, 5, 'Date : ' . $dateFacturation, 0, 1, 'C');
        $this->Ln(15);
    }





    /**
     * @param CommandeEntity $entiteCommande
     */
    private function setNumeroFacture (CommandeEntity $entiteCommande)
    {
        $this->SetCol(0);
        $this->SetFont('DejaVu','',10);
        $this->Cell(100, 5, 'Pièce n° : ' . $entiteCommande->numero_facture());
        $this->Ln(10);
    }

    /**
     * @param CommandeEntity $entiteCommande
     */
    private function ajoutArticles(CommandeEntity $entiteCommande)
    {
        // Couleurs, épaisseur du trait et police grasse
        $this->SetFillColor(255,0,0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128,0,0);
        $this->SetLineWidth(.3);
        $this->SetFont('','B');
        // En-tête
        $entetesColonnes = ['Produit(s) et/ou Prestations', 'Prix unitaire', 'Quantité', 'Total'];
        $w = array(110, 25, 25, 30);
        for($i=0; $i<count($entetesColonnes); $i++)
            $this->Cell($w[$i],7,$entetesColonnes[$i],1,0,'C',true);
        $this->Ln();
        // Restauration des couleurs et de la police
        $this->SetFillColor(224,235,255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Données
        $fill = false;

        $newLignesDeCommandeManager = new LignesDeCommandesManager();
        $allLignesDeCommandeFromCommandeId = $newLignesDeCommandeManager->getAllLignesDeCommandeFromOneCommande($entiteCommande->id());

        foreach ($allLignesDeCommandeFromCommandeId as $ligneDeCommande)
        {
            $this->Cell($w[0],6,$ligneDeCommande->photo_name(),'LR',0,'L',$fill);
            $this->Cell($w[1],6,number_format($ligneDeCommande->tarif(),2,',',' '),'LR',0,'L',$fill);
            $this->Cell($w[2],6,number_format($ligneDeCommande->nombre_exemplaires(),0,',',' '),'LR',0,'R',$fill);
            $this->Cell($w[3],6,number_format($ligneDeCommande->tarif() * $ligneDeCommande->nombre_exemplaires(),2,',',' '),'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }

        // Trait de terminaison
        $this->Cell(array_sum($w),0,'','T');
    }


    /**
     * @param CommandeEntity $entiteCommande
     */
    private function setFraisDePortEtTotal (CommandeEntity $entiteCommande)
    {

        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->SetX(145);
        $this->Cell(25, 5, 'Frais de port', 0, 0, 'R');
        $this->Cell(30, 5, number_format(Utilitaires::FRAIS_DE_PORT, 2, ',', ' '), 0, 1, 'R');
        $this->SetX(145);
        $this->Cell(25, 5, 'Total HT', 1, 0, 'R');
        $this->Cell(30, 5, number_format($entiteCommande->montant_total() + Utilitaires::FRAIS_DE_PORT, 2, ',', ' '), 1, 1, 'R');
        $this->SetFont('DejaVu','',8);
        $this->SetX(145);
        $this->Cell(55, 5, 'TVA non applicable, art. 293 B du CGI', 0, 0, 'C');
        $this->Ln(10);
    }


    /**
     * @param CommandeEntity $commandeEntity
     */
    private function setDateEtModeReglement (CommandeEntity $commandeEntity)
    {
        $dateFacturation = $this->SetDate($commandeEntity);
        $this->SetFont('DejaVu','',10);
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Date de règlement : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, $dateFacturation, 0, 1, 'L');
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Date d\'exécution de la vente : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, $dateFacturation, 0, 1, 'L');
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Mode de règlement : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, self::PAYPAL, 0, 1, 'L');
    }



}