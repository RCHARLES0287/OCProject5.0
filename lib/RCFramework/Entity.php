<?php


namespace RCFramework;


class Entity implements \ArrayAccess
{
    protected $isEmpty,
        $id;
//    Le CLASS_PREFIX est alimenté dans les classes filles
    protected const CLASS_PREFIX = '';


    public function __construct(array $donnees = [])
    {
        $this->hydrate($donnees);
        if (count($donnees) === 0)
        {
            $this->isEmpty = true;
        }
        else
        {
            $this->isEmpty = false;
        }
    }

    public function isNew()
    {
        return Utilitaires::emptyMinusZero($this->id);
    }

    public function testEntityExists()
    {
        return !$this->isEmpty;
    }

    public function id()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = (int)$id;
    }

    public function hydrate(array $donnees)
    {
        foreach ($donnees as $attribut => $valeur)
        {
//            Ici on ôte le préfixe de chaque attribut (préfixe correspondant au nom de la table dans la BDD) afin d'hydrater les propriété de l'entité
            $methode = 'set' . ucfirst(substr($attribut, strlen(static::CLASS_PREFIX) + 1));

            if (is_callable([$this, $methode]))
            {
                $this->$methode($valeur);
            }
        }
    }

    public function offsetGet($var)
    {
        if (isset($this->$var) && is_callable([$this, $var]))
        {
            return $this->$var(); // c'est en fait le getter que l'on va appeler ici. On peut d'ailleurs appeler les getters "getVar()" par exemple.
        }
    }

    public function offsetSet($var, $value)
    {
        $method = 'set' . ucfirst($var);

        if (isset($this->$var) && is_callable([$this, $method]))
        {
            $this->$method($value);
        }
    }

    public function offsetExists($var)
    {
        return isset($this->$var) && is_callable([$this, $var]);
    }

    public function offsetUnset($var)
    {
        throw new \Exception('Impossible de supprimer une quelconque valeur');
    }
}