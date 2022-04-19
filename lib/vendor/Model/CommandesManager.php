<?php


namespace Model;


use Entity\CommandeEntity;
use RCFramework\Manager;
use RCFramework\Utilitaires;

class CommandesManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getAllCommandes()
    {
        $answerCommandesData = $this->db->prepare('SELECT commandes_id, commandes_numero_commande, commandes_numero_facture, commandes_montant_total, commandes_id_utilisateur, commandes_nom_et_prenom_utilisateur, commandes_adresse_utilisateur, commandes_validation_panier
                                                            FROM rc_photographe_commandes');
        $answerCommandesData->execute();

        $commandesFeatures = [];

        $dbCommandes = $answerCommandesData->fetchAll();

        foreach ($dbCommandes as $commande)
        {
            $commandesFeatures[] = new CommandeEntity($commande);
        }

        return $commandesFeatures;
    }


    public function getOneCommande($idCommande)
    {
        $answerCommandeData = $this->db->prepare('SELECT *
                                                            FROM rc_photographe_commandes
                                                            WHERE commandes_id=:idCommande');
        $answerCommandeData->execute(array(
            'idCommande' => $idCommande
        ));

        $dbCommande = $answerCommandeData->fetch();

        if ($dbCommande === false)
        {
            throw new \Exception('La commande avec l\'Id de commande ' . $idCommande . ' n\'existe pas');
        }
        else
        {
            Utilitaires::logMessage("Dans le else");
            Utilitaires::logMessage(serialize($dbCommande));
            return new CommandeEntity($dbCommande);
        }

    }


    public function saveOneCommande(CommandeEntity &$newCommandeEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_commandes(commandes_numero_commande, commandes_numero_facture, commandes_montant_total, commandes_id_utilisateur, commandes_nom_et_prenom_utilisateur, commandes_adresse_utilisateur, commandes_date_facturation)
                                            VALUES (:numero_commande, :numero_facture, :montant_total, :id_utilisateur, :nom_et_prenom_utilisateur, :adresse_utilisateur, :date_facturation)');
        $req->execute(array(
            'numero_commande' => $newCommandeEntity->numero_commande(),
            'numero_facture' => $newCommandeEntity->numero_facture(),
            'montant_total' => $newCommandeEntity->montant_total(),
            'id_utilisateur' => $newCommandeEntity->id_utilisateur(),
            'nom_et_prenom_utilisateur' => $newCommandeEntity->nom_et_prenom_utilisateur(),
            'adresse_utilisateur' => $newCommandeEntity->adresse_utilisateur(),
            'date_facturation' => $newCommandeEntity->date_facturation()
        ));
//        lastInsertId sert à alimenter automatiquement l'id de l'entité qui a été passée en paramètre suite à sa création dans la BDD
        $newCommandeEntity->setId($this->db->lastInsertId());
    }


    public function updateCommande(CommandeEntity $modifiedCommande)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_commandes
                                            SET commandes_numero_commande=:numero_commande,
                                                commandes_numero_facture=:numero_facture,
                                                commandes_montant_total=:montant_total,
                                                commandes_id_utilisateur=:id_utilisateur,
                                                commandes_nom_et_prenom_utilisateur=:nom_et_prenom_utilisateur,
                                                commandes_adresse_utilisateur=:adresse_utilisateur,
                                                commandes_date_facturation=:date_facturation
                                            WHERE commandes_id=:commandId');
        $req->execute(array(
            'numero_commande' => $modifiedCommande->numero_commande(),
            'numero_facture' => $modifiedCommande->numero_facture(),
            'montant_total' => $modifiedCommande->montant_total(),
            'id_utilisateur' => $modifiedCommande->id_utilisateur(),
            'nom_et_prenom_utilisateur' => $modifiedCommande->nom_et_prenom_utilisateur(),
            'adresse_utilisateur' => $modifiedCommande->adresse_utilisateur(),
            'date_facturation' => $modifiedCommande->date_facturation(),
            'commandId' => $modifiedCommande->id()
        ));
        /*var_dump("jusque là ça marche");
        exit;*/
    }
}

