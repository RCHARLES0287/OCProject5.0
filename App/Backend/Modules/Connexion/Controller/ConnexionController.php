<?php


namespace App\Backend\Modules\Connexion\Controller;


use Model\AdministrateurManager;
use RCFramework\BackController;
use RCFramework\HTTPRequest;
use RCFramework\Utilitaires;

class ConnexionController extends BackController
{
    public function executeAdminconnexion (HTTPRequest $request)
    {

    }

    public function executeAdminconnexionform (HTTPRequest $request)
    {
        if (!Utilitaires::emptyMinusZero($request->postData('email_adress'))
            && !Utilitaires::emptyMinusZero($request->postData('password')))
        {
            /*var_dump('On est bien dans le controller de connexion admin');
            exit;*/

            $AdministrateurManager = new AdministrateurManager();

            $SQLRequest = 'SELECT * FROM rc_photographe_admins WHERE admins_login=:adminLogin';

            /*var_dump($request->postData('password'));
            exit;*/

            $administrateurEntity = $AdministrateurManager->compareIdentificationWithDb($request->postData('email_adress'),
                                                                                    $request->postData('password'),
                                                                                    $SQLRequest,
                                                                                    'adminLogin');

            /*var_dump($administrateurEntity);
            exit;*/

            if ($administrateurEntity !== null)
            {
                $_SESSION['administrateur_entity'] = $administrateurEntity;
                $_SESSION['connexion_admin_status'] = true;
            }
            else
            {
                throw new \Exception('Erreur dans l\'authentification de l\'administrateur');
            }
        }
    }

    public function executeAdmindeconnexion (HTTPRequest $request)
    {
        $_SESSION['connexion_admin_status'] = false;
        header('Location: /');
    }

    /**
     * A utiliser à chaque fois qu'on a besoin de savoir si l'administrateur est connecté ou non
     * @return bool Si true est renvoyé alors l'administrateur est connecté, si false est renvoyé alors il n'y a pas d'administrateur connecté
     */
    static public function isAdminConnected(): bool
    {
//        Ici return ne renvoie pas le contenu de $_SESSION['connexion_admin_status'], mais il renvoie le resultat du double test
//          qui se trouve être true (donc équivalent au contenu de $_SESSION['connexion_admin_status'].
        return isset($_SESSION['connexion_admin_status']) && $_SESSION['connexion_admin_status'] == true;
    }
}
