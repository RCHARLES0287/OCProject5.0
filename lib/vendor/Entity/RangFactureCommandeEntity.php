<?php


namespace Entity;


use RCFramework\Entity;


class RangFactureCommandeEntity extends Entity
{
    //    CLASS_PREFIX contient le préfixe de la table
    protected const CLASS_PREFIX = 'rangs_commandes';
    private string $numero_commande;
    private string $numero_facture;

    public function setNumero_commande($numero_commande)
    {
        $this->numero_commande = $numero_commande;
    }

    public function numero_commande():string
    {
        return $this->numero_commande;
    }


    public function setNumero_facture($numero_facture)
    {
        $this->numero_facture = $numero_facture;
    }

    public function numero_facture():string
    {
        return $this->numero_facture;
    }

}

