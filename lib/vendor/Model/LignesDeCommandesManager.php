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
        $answerLignesCommandes = $this->db->prepare('SELECT lignes_de_commandes_id, lignes_de_commandes_commande_id, lignes_de_commandes_nom_prenom_adresse, lignes_de_commandes_photo_serial_number, lignes_de_commandes_photo_name, lignes_de_commandes_dimensions, lignes_de_commandes_tarifs, lignes_de_commandes_nombre_exemplaires
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


    public function saveOneLigneDeCommande(Ligne_de_commandeEntity $newLigneDeCommandeEntity)
    {
        $req = $this->db->prepare('INSERT INTO rc_photographe_lignes_de_commandes(lignes_de_commandes_commande_id, lignes_de_commandes_nom_prenom_adresse, lignes_de_commandes_photo_serial_number, lignes_de_commandes_photo_name, lignes_de_commandes_dimensions, lignes_de_commandes_tarifs, lignes_de_commandes_nombre_exemplaires)
                                            VALUES (:lignes_de_commandes_commande_id, :lignes_de_commandes_nom_prenom_adresse, :lignes_de_commandes_photo_serial_number, :lignes_de_commandes_photo_name, :lignes_de_commandes_dimensions, :lignes_de_commandes_tarifs, :lignes_de_commandes_nombre_exemplaires)');
        $req->execute(array(
            'lignes_de_commandes_commande_id' => $newLigneDeCommandeEntity->commande_id(),
            'lignes_de_commandes_nom_prenom_adresse' => $newLigneDeCommandeEntity->nom_prenom_adresse(),
            'lignes_de_commandes_photo_serial_number' => $newLigneDeCommandeEntity->photo_serial_number(),
            'lignes_de_commandes_photo_name' => $newLigneDeCommandeEntity->photo_name(),
            'lignes_de_commandes_dimensions' => $newLigneDeCommandeEntity->dimensions(),
            'lignes_de_commandes_tarifs' => $newLigneDeCommandeEntity->tarif(),
            'lignes_de_commandes_nombre_exemplaires' => $newLigneDeCommandeEntity->nombre_exemplaires()
        ));
    }


    public function updateLigneDeCommande(Ligne_de_commandeEntity $ligneDeCommandeEntity, $ligneDeCommandeId)
    {
        $req = $this->db->prepare('UPDATE rc_photographe_lignes_de_commandes
                                            SET lignes_de_commandes_commande_id=:lignesDeCommandesCommandeId, lignes_de_commandes_nom_prenom_adresse=:lignesDeCommandesNomPrenomAdresse, lignes_de_commandes_photo_serial_number=:lignesDeCommandesPhotoSerialNumber, lignes_de_commandes_photo_name=:lignesDeCommandesPhotoName, lignes_de_commandes_dimensions=:lignesDeCommandesDimensions, lignes_de_commandes_tarifs=:lignesDeCommandesTarifs, lignes_de_commandes_nombre_exemplaires=:lignesDeCommandesNombreExemplaires
                                            WHERE lignes_de_commandes_id=:lignesDeCommandesId');
        $req->execute(array(
            'lignesDeCommandesId' => $ligneDeCommandeId,
            'lignesDeCommandesCommandeId' => $ligneDeCommandeEntity->commande_id(),
            'lignesDeCommandesNomPrenomAdresse' => $ligneDeCommandeEntity->nom_prenom_adresse(),
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

