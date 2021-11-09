<?php

namespace RCFramework;

use tFPDF;



class FacturePDF extends tFPDF
{
    protected float $col = 0; // Colonne courante
    protected float $y0;      // Ordonnée du début des colonnes
    protected float $y0bloc;  // Ordonnée du début du bloc
    const PAYPAL = 'Paypal';


    public function __construct($nomClient, $adresseClient, $complementInfoClient, $date, $numeroFacture,
                                $tableauData, $fraisDePort, $total, $cheminFacture)
    {
        parent::__construct();
        $this->AddFont('DejaVu','','DejaVuSansCondensed.ttf',true);
        $this->AddFont('DejaVu','B','DejaVuSansCondensed-Bold.ttf',true);

        $this->AddPage();
        $this->complementEntete($nomClient, $adresseClient, $complementInfoClient);
        $this->writeParagrapheLegal();
        $this->setDate($date);
        $this->setNumeroFacture($numeroFacture);
        $this->ajoutArticles($tableauData);
        $this->setFraisDePortEtTotal ($fraisDePort, $total);
        $this->setDateEtModeReglement($date);
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

    protected function complementEntete ($nomClient, $adresseClient, $complementInfoClient)
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
        $enteteClient = $nomClient . "\n" . $adresseClient . "\n" . $complementInfoClient;
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $largeurNomClient = $this->GetStringWidth($nomClient);
        $largeurAdresseClient = $this->GetStringWidth($adresseClient);
        $largeurComplementInfoClient = $this->GetStringWidth($complementInfoClient);

        $largeurEnteteVendeurEtClient = max($largeurNomClient, $largeurAdresseClient, $largeurComplementInfoClient);
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

    protected function SetCol($col)
    {
        // Positionnement sur une colonne
        $this->col = $col;
        $x = 10+$col*($this->GetPageWidth()/2);
        $this->SetLeftMargin($x);
        $this->SetX($x);
    }

    protected function writeParagrapheLegal ()
    {
        $this->y0bloc = $this->GetY();
        $this->SetX(10);
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->MultiCell($this->GetPageWidth()/3, 5,
            'Dispensé d\'immatriculation au registre du commerce et des sociétés (RCS) et au répertoire des métiers',
            0,'J','');
    }

    protected function setDate ($date)
    {
        $this->SetCol(1);
        $this->SetY($this->y0bloc+5);
        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->Cell(100, 5, 'Date : ' . $date, 0, 1, 'C');
        $this->Ln(15);
    }

    protected function setNumeroFacture ($numeroFacture)
    {
        $this->SetCol(0);
        $this->SetFont('DejaVu','',10);
        $this->Cell(100, 5, 'Pièce n° : ' . $numeroFacture);
        $this->Ln(10);
    }

    protected function ajoutArticles($tableauData)
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
        foreach($tableauData as $row)
        {
            $this->Cell($w[0],6,$row[0],'LR',0,'L',$fill);
            $this->Cell($w[1],6,number_format($row[1],2,',',' '),'LR',0,'L',$fill);
            $this->Cell($w[2],6,number_format($row[2],0,',',' '),'LR',0,'R',$fill);
            $this->Cell($w[3],6,number_format($row[3],2,',',' '),'LR',0,'R',$fill);
            $this->Ln();
            $fill = !$fill;
        }
        // Trait de terminaison
        $this->Cell(array_sum($w),0,'','T');
    }

    protected function setFraisDePortEtTotal ($fraisDePort, $total)
    {

        $this->SetFont('DejaVu','',10);
        $this->SetLineWidth(1);
        $this->SetX(145);
        $this->Cell(25, 5, 'Frais de port', 0, 0, 'R');
        $this->Cell(30, 5, number_format($fraisDePort, 2, ',', ' '), 0, 1, 'R');
        $this->SetX(145);
        $this->Cell(25, 5, 'Total HT', 1, 0, 'R');
        $this->Cell(30, 5, number_format($total, 2, ',', ' '), 1, 1, 'R');
        $this->SetFont('DejaVu','',8);
        $this->SetX(145);
        $this->Cell(55, 5, 'TVA non applicable, art. 293 B du CGI', 0, 0, 'C');
        $this->Ln(10);
    }

    protected function setDateEtModeReglement ($date)
    {
        $this->SetFont('DejaVu','',10);
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Date de règlement : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, $date, 0, 1, 'L');
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Date d\'exécution de la vente : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, $date, 0, 1, 'L');
        $this->y0bloc = $this->GetY();
        $this->Cell(65, 5, 'Mode de règlement : ', 0, 0, 'L');
        $this->SetY($this->y0bloc);
        $this->SetX(67);
        $this->Cell(50, 5, self::PAYPAL, 0, 1, 'L');
    }



}