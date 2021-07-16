<?php


namespace Model;


use Entity\CommandeEntity;
use RCFramework\Manager;

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


    public function getOneCommande($numeroCommande)
    {
        $answerCommandeData = $this->db->prepare('SELECT commandes_id, commandes_numero_commande, commandes_numero_facture, commandes_montant_total, commandes_id_utilisateur, commandes_nom_et_prenom_utilisateur, commandes_adresse_utilisateur, commandes_validation_panier
                                                            FROM rc_photographe_commandes
                                                            WHERE numero_commande=:numeroCommande');
        $answerCommandeData->execute(array(
            'numeroCommande' => $numeroCommande
        ));

        $dbCommande = $answerCommandeData->fetch();

        if ($dbCommande === false)
        {
            throw new \Exception('La commande dont le numéro de commande est ' . $numeroCommande . ' n\'existe pas');
        }
        else
        {
            return new CommandeEntity($dbCommande);
        }
    }


    public function saveOneCommande(CommandeEntity $newCommandeEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_commandes(commandes_numero_commande, commandes_numero_facture, commandes_montant_total, commandes_id_utilisateur, commandes_nom_et_prenom_utilisateur, commandes_adresse_utilisateur, commandes_validation_panier)
                                            VALUES (:numero_commande, :numero_facture, :montant_total, :id_utilisateur, :nom_et_prenom_utilisateur, :adresse_utilisateur, :validation_panier)');
        $req->execute(array(
            'numero_commande' => $newCommandeEntity->numero_commande(),
            'numero_facture' => $newCommandeEntity->numero_facture(),
            'montant_total' => $newCommandeEntity->montant_total(),
            'id_utilisateur' => $newCommandeEntity->id_utilisateur(),
            'nom_et_prenom_utilisateur' => $newCommandeEntity->nom_et_prenom_utilisateur(),
            'adresse_utilisateur' => $newCommandeEntity->adresse_utilisateur(),
            'validation_panier' => $newCommandeEntity->validation_panier()
        ));
    }


    public function updateCommande(CommandeEntity $modifiedCommande, $commandId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_commandes
                                            SET commandes_numero_commande=:numero_commande, commandes_numero_facture=:numero_facture, commandes_=:montant_total, commandes_id_utilisateur=:id_utilisateur, commandes_nom_et_prenom_utilisateur=:nom_et_prenom_utilisateur, commandes_adresse_utilisateur=:adresse_utilisateur, commandes_validation_panier=:validation_panier
                                            WHERE commandes_id=:commandId');
        $req->execute(array(
            'numero_commande' => $modifiedCommande->numero_commande(),
            'numero_facture' => $modifiedCommande->numero_facture(),
            'montant_total' => $modifiedCommande->montant_total(),
            'id_utilisateur' => $modifiedCommande->id_utilisateur(),
            'nom_et_prenom_utilisateur' => $modifiedCommande->nom_et_prenom_utilisateur(),
            'adresse_utilisateur' => $modifiedCommande->adresse_utilisateur(),
            'validation_panier' => $modifiedCommande->validation_panier()
        ));
    }
}

