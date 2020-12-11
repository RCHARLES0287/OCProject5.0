<?php


namespace RCFramework;


abstract class Utilitaires
{
    public static function emptyMinusZero ($value)
    {
        if ($value == '')       //Contrôle les chaînes vides
        {
            return true;
        }
        else if (is_string($value) && trim($value) === '')       //Contrôle les chaînes d'espaces blancs
        {
            return true;
        }
        else if ($value === null)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
