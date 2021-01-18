<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class KeywordEntity extends Entity
{
    private string $keyword;

    public function setKeyword(string $keyword)
    {
        if (Utilitaires::emptyMinusZero($keyword))
        {
            throw new \Exception('Un mot clé doit être renseigné');
        }
    }

    public function keyword():string
    {
        return $this->keyword;
    }
}
