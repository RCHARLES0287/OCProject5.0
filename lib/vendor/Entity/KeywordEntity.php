<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class KeywordEntity extends Entity
{
    protected const CLASS_PREFIX = 'keywords';
    private string $keyword;

    public function setKeyword($keyword)
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
