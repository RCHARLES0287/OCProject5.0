<?php


namespace RCFramework;


class Entity implements \ArrayAccess
{
    protected $erreurs = [],
        $id;

    public function __construct(array $donnees = [])
    {
        if (!Utilitaires::emptyMinusZero($donnees))
        {
            $this->hydrate($donnees);
        }
    }

    public function isNew()
    {
        return Utilitaires::emptyMinusZero($this->id);
    }

    public function erreurs()
    {
        return $this->erreurs;
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
            $methode = 'set' . ucfirst($attribut);

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