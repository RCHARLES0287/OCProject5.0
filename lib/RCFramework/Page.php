<?php


namespace RCFramework;


class Page extends ApplicationComponent
{
    protected $contentFile;
    protected $vars = [];

    /**
     * Permet d'ajouter une variable à la vue
     *
     * @param string $var c'est le nom de la variable
     * @param mixed $value c'est la valeur de la variable
     */
    public function addVar($var, $value)
    {
        if (!is_string($var) || is_numeric($var) || Utilitaires::emptyMinusZero($var))
        {
            throw new \InvalidArgumentException('Le nom de la variable doit être une chaine de caractères non nulle');
        }

        $this->vars[$var] = $value;
    }

    public function getGeneratedPage()    // Lire le contenu du fichier vue, le renvoyer sous la forme d'une chaîne de caractères. Applique en automatique le layout.
    {
        if (!file_exists($this->contentFile))
        {
            throw new \RuntimeException('La vue ' . $this->contentFile . ' n\'existe pas');
        }


        extract($this->vars);

        ob_start();
        require $this->contentFile;
        $content = ob_get_clean();

        ob_start();
        require __DIR__ . '/../../App/' . $this->app->name() . '/Templates/layout.php';
        return ob_get_clean();
    }

    public function setContentFile($contentFile)
    {
        if (!is_string($contentFile) || Utilitaires::emptyMinusZero($contentFile))
        {
            throw new \InvalidArgumentException('La vue spécifiée est invalide');
        }

        $this->contentFile = $contentFile;
    }
}