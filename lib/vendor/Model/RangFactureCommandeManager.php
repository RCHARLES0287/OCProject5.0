<?php


namespace Model;


use Entity\RangFactureCommandeEntity;
use RCFramework\Manager;

class RangFactureCommandeManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public const COMMANDE = 'commande';
    public const FACTURE = 'facture';

    private function getCurrentNumerosFactureCommande()
    {
//        $this->db->beginTransaction();
        $answerNumerosFactureCommandeData = $this->db->prepare('SELECT * FROM rc_photographe_rangs_commandes');

        $answerNumerosFactureCommandeData->execute();

        $dbNumerosFactureCommande = $answerNumerosFactureCommandeData->fetch();

        return new RangFactureCommandeEntity($dbNumerosFactureCommande);

    }


    /**
     * @param $typeOfData string type de données que l'on recherche : soit 'facture' soit 'commande'
     * @return string Retourne le rang de facture ou de commande actuel
     * @throws \Exception
     */
    public function getAndUpdateCurrentNumeroFactureCommande(string $typeOfData)
    {
        $this->db->beginTransaction();

        $answerNumerosFactureCommandeData = $this->db->prepare('SELECT * FROM rc_photographe_rangs_commandes');

        $answerNumerosFactureCommandeData->execute();

        $dbNumerosFactureCommande = $answerNumerosFactureCommandeData->fetch();

        $rangFactureCommandeEntity = new RangFactureCommandeEntity($dbNumerosFactureCommande);

        switch ($typeOfData)
        {
            case self::FACTURE:
                $getter = 'numero_facture';
                break;

            case self::COMMANDE:
                $getter = 'numero_commande';
                break;

            default:
                $this->db->rollBack();
                throw new \Exception('Erreur survenue dans le choix du paramètre à modifier, facture ou commande');
        }
        $this->updateNumerosFactureCommande($typeOfData, $rangFactureCommandeEntity->$getter());

        $this->db->commit();
        return $rangFactureCommandeEntity->$getter();
    }


    private function updateNumerosFactureCommande($dataToUpdate, $previousRank)
    {
        if ($dataToUpdate === 'facture')
        {
            $requeteSQL = 'UPDATE rc_photographe_rangs_commandes
                            SET rangs_commandes_numero_facture=:numero_facture';
            $correspondanceDataType = 'numero_facture';
        }
        else if ($dataToUpdate === 'commande')
        {
            $requeteSQL = 'UPDATE rc_photographe_rangs_commandes
                            SET rangs_commandes_numero_commande=:numero_commande';
            $correspondanceDataType = 'numero_commande';
        }
        else
        {
            throw new \Exception('Erreur survenue dans le cas à traiter, facture ou commande');
        }
        $req = $this->db->prepare($requeteSQL);
        $req->execute(array(
            $correspondanceDataType => $previousRank + 1
        ));
    }

    /*
    public function getAndUpdateCurrentNumeroFacture()
    {
        $this->db->beginTransaction();

        $rangFactureCommandeEntity = $this->getCurrentNumerosFactureCommande();

        $req = $this->db->prepare('UPDATE rc_photographe_rangs_commandes
                                            SET rangs_commandes_numero_facture=:numero_facture');
        $req->execute(array(
            'numero_facture' => $rangFactureCommandeEntity->numero_facture() + 1
        ));

        $this->db->commit();
        return $rangFactureCommandeEntity;
    }

    public function getAndUpdateCurrentNumeroCommande()
    {
        $this->db->beginTransaction();

        $rangFactureCommandeEntity = $this->getCurrentNumerosFactureCommande();

        $req = $this->db->prepare('UPDATE rc_photographe_rangs_commandes
                                            SET rangs_commandes_numero_commande=:numero_commande');
        $req->execute(array(
            'numero_commande' => $rangFactureCommandeEntity->numero_commande() + 1
        ));

        $this->db->commit();
        return $rangFactureCommandeEntity;
    }
    */

}