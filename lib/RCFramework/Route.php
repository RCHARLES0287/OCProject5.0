<?php


namespace RCFramework;


class Route
{
    protected $action;
    protected $module;
    protected $url;

    public function __construct($url, $module, $action)
    {
        $this->setUrl($url);
        $this->setModule($module);
        $this->setAction($action);
    }


    public function match($url)
    {
//      La regex s'assure de correspondre à la route, et de ne pas matcher vers l'url de base en s'assurant qu'il n'y a rien derrière ou des arguments du get ou une ancre
        if (preg_match('`^' . preg_quote($this->url, "`") . '(?=[?#]|$)`i', $url))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function setAction($action)
    {
        if (is_string($action))
        {
            $this->action = $action;
        }
    }

    public function setModule($module)
    {
        if (is_string($module))
        {
            $this->module = $module;
        }
    }

    public function setUrl($url)
    {
        if (is_string($url))
        {
            $this->url = $url;
        }
    }

    public function action()
    {
        return $this->action;
    }

    public function module()
    {
        return $this->module;
    }

}