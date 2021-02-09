<?php


namespace Entity;


use RCFramework\Entity;
use RCFramework\Utilitaires;

class DimensionsEntity extends Entity
{
    protected const CLASS_PREFIX = 'dimensions';
    private string $dimensions;

    public function setDimensions($dimensions)
    {
        if (Utilitaires::emptyMinusZero($dimensions))
        {
            throw new \Exception('Des dimensions doivent être définies');
        }
        else
        {
            $this->dimensions = $dimensions;
        }
    }

    public function dimensions():string
    {
        return $this->dimensions;
    }
}