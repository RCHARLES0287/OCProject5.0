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
//        Charge la bibliothèque Twig
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/../../App');
//        Charge l'objet Twig
        $twig = new \Twig\Environment($loader, [
            'cache' => false,
//            'cache' => __DIR__.'/../../cache/twig'
        ]);

        ob_start();


///TODO Penser à faire un try..catch sur le echo ci-dessous
        echo $twig->render($this->contentFile, $this->vars);

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