<?php


namespace Entity;


use RCFramework\Entity;


class RangFactureCommandeEntity extends Entity
{
    //    CLASS_PREFIX contient le préfixe de la table
    protected const CLASS_PREFIX = 'rangs_commandes';
    private int $numero_commande;
    private int $numero_facture;

    public function setNumeros_commandes($numero_commande)
    {
        $this->numero_commande = $numero_commande;
    }

    public function numero_commande():int
    {
        return $this->numero_commande;
    }


    public function setNumero_facture($numero_facture)
    {
        $this->numero_facture = $numero_facture;
    }

    public function numero_facture():int
    {
        return $this->numero_facture;
    }

}
