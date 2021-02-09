<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class Photo_keywordsEntity extends Entity
{
    protected const CLASS_PREFIX = 'photos_keywords';
    private int $photo_id;
    private array $keywords_id;

    public function setPhoto_id($photo_id)
    {
        if (Utilitaires::emptyMinusZero($photo_id))
        {
            throw new \Exception("L'id de la photo doit être renseigné");
        }
        else
        {
            $this->photo_id = $photo_id;
        }
    }

    public function numero_commande(): int
    {
        return $this->photo_id;
    }


    public function setKeywords_id($keywords_id)
    {
        if (Utilitaires::emptyMinusZero($keywords_id))
        {
            throw new \Exception("Au moins un mot clé doit être défini");
        }
        else
        {
            $this->keywords_id = $keywords_id;
        }
    }

    public function keywords_id():array
    {
        return $this->keywords_id;
    }
}
