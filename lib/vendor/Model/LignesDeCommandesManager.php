<?php


namespace Model;


use Entity\Ligne_de_commandeEntity;
use RCFramework\Manager;

class LignesDeCommandesManager extends Manager
{
    public function __construct()
    {
        parent::__construct();
    }


    public function getAllLignesDeCommandes()
    {
        $answerLignesCommandes = $this->db->prepare('SELECT *
                                                                FROM rc_photographe_lignes_de_commandes');
        $answerLignesCommandes->execute();

        $lignesCommandesFeatures = [];

        $dbLignesCommandes = $answerLignesCommandes->fetchAll();

        foreach ($dbLignesCommandes as $ligneCommande)
        {
            $lignesCommandesFeatures[] = new Ligne_de_commandeEntity($ligneCommande);
        }

        return $lignesCommandesFeatures;
    }

    public function getAllLignesDeCommandeFromOneCommande($commandeId)
    {
        $answerLignesDeCommandeData = $this->db->prepare('SELECT * FROM rc_photographe_lignes_de_commandes WHERE lignes_de_commandes_commande_id=:commandeId');
        $answerLignesDeCommandeData->execute(array(
            'commandeId' => $commandeId
        ));

        $lignesDeCommandeFeatures = [];

        $dbLignesDeCommande = $answerLignesDeCommandeData->fetchAll();

        foreach ($dbLignesDeCommande as $ligneDeCommande)
        {
            $lignesDeCommandeFeatures[] = new Ligne_de_commandeEntity($ligneDeCommande);
        }

        return $lignesDeCommandeFeatures;
    }


    public function saveOneLigneDeCommande(Ligne_de_commandeEntity &$newLigneDeCommandeEntity)
    {
        /*$req = $this->db->prepare('INSERT INTO rc_photographe_lignes_de_commandes(lignes_de_commandes_commande_id, lignes_de_commandes_nom_prenom_adresse, lignes_de_commandes_photo_serial_number, lignes_de_commandes_photo_name, lignes_de_commandes_dimensions, lignes_de_commandes_tarif, lignes_de_commandes_nombre_exemplaires)
                                            VALUES (:lignes_de_commandes_commande_id, :lignes_de_commandes_nom_prenom_adresse, :lignes_de_commandes_photo_serial_number, :lignes_de_commandes_photo_name, :lignes_de_commandes_dimensions, :lignes_de_commandes_tarif, :lignes_de_commandes_nombre_exemplaires)');*/
        /*$req = $this->db->prepare('INSERT INTO rc_photographe_lignes_de_commandes(lignes_de_commandes_nom_prenom_adresse, lignes_de_commandes_photo_serial_number, lignes_de_commandes_photo_name, lignes_de_commandes_dimensions, lignes_de_commandes_tarif, lignes_de_commandes_nombre_exemplaires)
                                            VALUES (:lignes_de_commandes_nom_prenom_adresse, :lignes_de_commandes_photo_serial_number, :lignes_de_commandes_photo_name, :lignes_de_commandes_dimensions, :lignes_de_commandes_tarif, :lignes_de_commandes_nombre_exemplaires)');*/
        $req = $this->db->prepare('INSERT INTO rc_photographe_lignes_de_commandes(lignes_de_commandes_commande_id, lignes_de_commandes_photo_serial_number, lignes_de_commandes_photo_name, lignes_de_commandes_dimensions, lignes_de_commandes_tarif, lignes_de_commandes_nombre_exemplaires)
                                            VALUES (:lignes_de_commandes_commande_id, :lignes_de_commandes_photo_serial_number, :lignes_de_commandes_photo_name, :lignes_de_commandes_dimensions, :lignes_de_commandes_tarif, :lignes_de_commandes_nombre_exemplaires)');
        $req->execute(array(
            'lignes_de_commandes_commande_id' => $newLigneDeCommandeEntity->commande_id(),
//            'lignes_de_commandes_nom_prenom_adresse' => $newLigneDeCommandeEntity->nom_prenom_adresse(),
            'lignes_de_commandes_photo_serial_number' => $newLigneDeCommandeEntity->photo_serial_number(),
            'lignes_de_commandes_photo_name' => $newLigneDeCommandeEntity->photo_name(),
            'lignes_de_commandes_dimensions' => $newLigneDeCommandeEntity->dimensions(),
            'lignes_de_commandes_tarif' => $newLigneDeCommandeEntity->tarif(),
            'lignes_de_commandes_nombre_exemplaires' => $newLigneDeCommandeEntity->nombre_exemplaires()
        ));
        //        lastInsertId sert à alimenter automatiquement l'id de l'entité qui a été passée en paramètre suite à sa création dans la BDD
        $newLigneDeCommandeEntity->setId($this->db->lastInsertId());
    }


    public function updateLigneDeCommande(Ligne_de_commandeEntity $ligneDeCommandeEntity, $ligneDeCommandeId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_lignes_de_commandes
                                            SET lignes_de_commandes_commande_id=:lignesDeCommandesCommandeId, lignes_de_commandes_photo_serial_number=:lignesDeCommandesPhotoSerialNumber, lignes_de_commandes_photo_name=:lignesDeCommandesPhotoName, lignes_de_commandes_dimensions=:lignesDeCommandesDimensions, lignes_de_commandes_tarifs=:lignesDeCommandesTarifs, lignes_de_commandes_nombre_exemplaires=:lignesDeCommandesNombreExemplaires
                                            WHERE lignes_de_commandes_id=:lignesDeCommandesId');
        $req->execute(array(
            'lignesDeCommandesId' => $ligneDeCommandeId,
            'lignesDeCommandesCommandeId' => $ligneDeCommandeEntity->commande_id(),
//            'lignesDeCommandesNomPrenomAdresse' => $ligneDeCommandeEntity->nom_prenom_adresse(),
            'lignesDeCommandesPhotoSerialNumber' => $ligneDeCommandeEntity->photo_serial_number(),
            'lignesDeCommandesPhotoName' => $ligneDeCommandeEntity->photo_name(),
            'lignesDeCommandesDimensions' => $ligneDeCommandeEntity->dimensions(),
            'lignesDeCommandesTarifs' => $ligneDeCommandeEntity->tarif(),
            'lignesDeCommandesNombreExemplaires' => $ligneDeCommandeEntity->nombre_exemplaires()
        ));
    }


    public function deleteLigneDeCommande($ligneDeCommandeId)
    {
        $req = $this->db->prepare('DELETE FROM rc_photographe_lignes_de_commandes WHERE lignes_de_commandes_id=:lignesDeCommandesId');
        $req->execute(array(
            'lignesDeCommandesId' => $ligneDeCommandeId
        ));
    }
}

