<?php


namespace App\Frontend\Modules\Commande\Controller;


use Entity\CommandeEntity;
use Entity\GalerieEntity;
use Entity\Ligne_de_commandeEntity;
use Entity\PhotoEntity;
use Model\CommandesManager;
use Model\DimensionsManager;
use Model\GaleriesManager;
use Model\LignesDeCommandesManager;
use Model\PhotosManager;
use Model\RangFactureCommandeManager;
use Model\TarifsManager;
use RCFramework\BackController;
use RCFramework\Entity;
use RCFramework\HTTPRequest;
use RCFramework\Mailing;
use RCFramework\Utilitaires;

class CommandeController extends BackController
{
    public function executeShowallavailablephotos(HTTPRequest $request)
    {
        $photosManager = new PhotosManager();
        $this->page->addVar('all_available_photos', $photosManager->getAllAvailablePhotos());

        $galeriesManager = new GaleriesManager();
        /* Version alternative

        $galeries = [];
        foreach ($availablePhotos as $photo)
        {
            if (!isset($galeries [$photo->galerie_id()]))
            {
                $galeries [$photo->galerie_id()] = $galeriesManager->getOneGalerie($photo->galerie_id());
            }

        }*/
        $this->page->addVar('galeries', $galeriesManager->getAllGaleries());
    }


    public function executeShowonearticle(HTTPRequest $request)
    {
        if ($request->getExists('photo_id') && $request->getExists('galerie_id'))
        {
//            Pour obtenir les infos de la photo
            $photosManager = new PhotosManager();
            $selectedPhoto = $photosManager->getOnePhoto($request->getData('photo_id'));
            $this->page->addVar('selected_photo', $selectedPhoto);

//            Pour obtenir tous les tarifs existants pour la photo
            $tarifsManager = new TarifsManager();
            $photoTarifs = $tarifsManager->getOnePhotoTarifs($request->getData('photo_id'));

            /*var_dump($photoTarifsRaw);
            exit();*/

            $dimensionsManager = new DimensionsManager();
            $allDimensions = $dimensionsManager->getAllDimensions();
            $this->page->addVar('allDimensions', $allDimensions);

//            Pour obtenir les infos de la galerie à laquelle est associée la photo
            $galerieManager = new GaleriesManager();
            $galerieEntity = $galerieManager->getOneGalerie($request->getData('galerie_id'));

            $this->page->addVar('photoTarifs', $photoTarifs);
            $this->page->addVar('galerieEntity', $galerieEntity);

            /*var_dump($photoTarifs);
            exit;*/
        }
        else
        {
            throw new \Exception('Accès à l\'article demandé refusé. Paramètre(s) fourni(s) erroné(s)');
        }
    }


    public function executeValidateonearticle (HTTPRequest $request)
    {
        if (isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles'])) {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];


                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                $isFound = false;
//                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi On peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId)
                    {
                        $lignePanier['nombreArticles'] += $nombreArticles;
                        $isFound = true;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }
                if ($isFound === false)
                {
                    $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];
                }


//        var_dump($_SESSION['panier']);

                /*
                $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();
                $newLigneDeCommande = new LignesDeCommandesManager();
                $newLigneDeCommande->saveOneLigneDeCommande();
                */
                echo json_encode(['status'=>'Succès', 'message'=>'Article ajouté au panier']);
            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur', 'message'=>'Echec de l\'ajout au panier']);
            }
        }
        else
        {
            Utilitaires::logMessage('Echec de l\'ajout au panier : paramètres incorrects');
            echo json_encode(['status'=>'Erreur', 'message'=>'Echec de l\'ajout au panier']);
        }

        header('Content-Type: application/json');
        exit;
    }


    public function executeAddorremoveonetoquantity (HTTPRequest $request)
    {
        if (isset($_POST['modifType']) && isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles']))
        {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];
                $modifType = $_POST['modifType'];

                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                $isFound = false;
                $newQuantity = 0;
                //                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi on peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId)
                    {
                        if ($modifType === 'add')
                        {
                            $lignePanier['nombreArticles'] += 1;
                        }
                        elseif ($modifType === 'remove' && $lignePanier['nombreArticles'] > 0)
                        {
                            $lignePanier['nombreArticles'] -= 1;
                        }
                        $newQuantity = $lignePanier['nombreArticles'];
                        $isFound = true;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }
//                Le cas où le panier ne contenait pas cet article dans ces dimensions
                if ($isFound === false)
                {
                    if ($modifType === 'add')
                    {
                        $_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles + 1];
                        $newQuantity = $nombreArticles +1;
                    }
//                    Pas de else car on est dans le cas où la ligne photo-dimensions n'existe pas dans le panier (donc impossible de soustraire)
                }
                echo json_encode(['status'=>'Succès', 'newQuantity'=>$newQuantity]);
            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur']);
            }
        }
        else
        {
            echo json_encode(['status'=>'Erreur']);
        }

        header('Content-Type: application/json');
        exit;
    }


    public function executeValidationpanier (HTTPRequest $request)
    {
        if (!isset($_SESSION['utilisateur_entity']) || !$_SESSION['utilisateur_entity']->testEntityExists())
        {
//            $_SESSION['loggingin_redirection'] contient le chemin vers lequel devra être effectuée la redirection
            $_SESSION['loggingin_redirection'] = '/validationpanier';
            header('Location: /logginginform');
            exit;
        }
        else if (isset($_SESSION['panier']))
        {
            $nbreTotalArticles = array_sum(array_map(function ($lignePanier)
            {
                return $lignePanier['nombreArticles'];
            }, $_SESSION['panier']));
            if ($nbreTotalArticles > 0)
            {
                $newRangFactureCommandeManager = new RangFactureCommandeManager();

                $newCommandeEntity = new CommandeEntity();
                $newCommandeEntity->setNumero_commande($newRangFactureCommandeManager->getAndUpdateCurrentNumeroFactureCommande(RangFactureCommandeManager::COMMANDE));
                $newCommandeEntity->setMontant_total(0);
                $newCommandeEntity->setId_utilisateur($_SESSION['utilisateur_entity']->id());
                $newCommandeEntity->setNom_et_prenom_utilisateur($_SESSION['utilisateur_entity']->nom(), $_SESSION['utilisateur_entity']->prenom());
                $newCommandeEntity->setAdresse_utilisateur($_SESSION['utilisateur_entity']->numero_rue(),
                    $_SESSION['utilisateur_entity']->nom_rue(),
                    $_SESSION['utilisateur_entity']->code_postal(),
                    $_SESSION['utilisateur_entity']->ville(),
                    $_SESSION['utilisateur_entity']->pays());
                $newCommandeEntity->setValidation_panier(0);

                $newCommandeManager = new CommandesManager();

//            L'id est alimenté en automatique dans saveOneCommande
                $newCommandeManager->saveOneCommande($newCommandeEntity);

//            $newCommandeEntity->setId($newCommandeManager->getOneCommande());

                $prixTotal = 0;

                foreach ($_SESSION['panier'] as $lignePanier)
                {
                    try
                    {
                        $articleId = $lignePanier['articleId'];
                        $dimensionsId = $lignePanier['dimensionsId'];
                        $nombreArticles = $lignePanier['nombreArticles'];

                        $newPhotoManager = new PhotosManager();
                        $newPhotoEntity = $newPhotoManager->getOnePhoto($articleId);

                        $newTarifsManager = new TarifsManager();
                        $newTarifsEntity = $newTarifsManager->getOnePhotoAndDimensionsTarif($articleId, $dimensionsId);
                        $tarif = $newTarifsEntity->prix();

                        /*var_dump($tarif);
                        exit;*/

                        $newLigneDeCommandeEntity = new Ligne_de_commandeEntity();

                        $prixLigneDeCommande = $nombreArticles * $tarif;
                        /*var_dump($prixLigneDeCommande);
                        exit;*/
                        $prixTotal = $prixTotal + $prixLigneDeCommande;


                        $newLigneDeCommandeEntity->setCommande_id($newCommandeEntity->id());
                        /*$newLigneDeCommandeEntity->setNom_prenom_adresse($_SESSION['utilisateur_entity']->nom(),
                            $_SESSION['utilisateur_entity']->prenom(),
                            $_SESSION['utilisateur_entity']->numero_rue(),
                            $_SESSION['utilisateur_entity']->nom_rue(),
                            $_SESSION['utilisateur_entity']->code_postal(),
                            $_SESSION['utilisateur_entity']->ville(),
                            $_SESSION['utilisateur_entity']->pays());*/
                        $newLigneDeCommandeEntity->setPhoto_serial_number($newPhotoEntity->serial_number());
                        $newLigneDeCommandeEntity->setPhoto_name($newPhotoEntity->name());
                        $newLigneDeCommandeEntity->setDimensions($dimensionsId);
                        $newLigneDeCommandeEntity->setTarif($tarif);
                        $newLigneDeCommandeEntity->setNombre_exemplaires($nombreArticles);

                        /*
                        var_dump($newLigneDeCommandeEntity);
                        exit;
                        */
                        $newLigneDeCommandeManager = new LignesDeCommandesManager();
                        $newLigneDeCommandeManager->saveOneLigneDeCommande($newLigneDeCommandeEntity);
                    }
                    catch (\Throwable $exception)
                    {
                        Utilitaires::logException($exception);
                        Utilitaires::returnJsonAndExit(['status'=>'Erreur']);
                    }
                }
                $newCommandeEntity->setMontant_total($prixTotal);
                $newCommandeManager->updateCommande($newCommandeEntity);
                unset($_SESSION['panier']);
                $_SESSION['commande'] = $newCommandeEntity;
                $this->page->addVar('commande_a_payer', $newCommandeEntity);
            }
            else
            {
                ///TODO Gérer le cas où la panier a une quantité égale à zéro
            }
        }
        else
        {
            $this->page->addVar('etatPanier', 'vide');
        }
    }


    public function executeConfirmationpaiement (HTTPRequest $request)
    {

    }


    public function executeAnnulationpaiement (HTTPRequest $request)
    {

    }


    public function executeRetourpaypal (HTTPRequest $request)
    {
        $req = "cmd=_notify-validate";
//        Ici, le $_POST correspond aux données renvoyées par Paypal suite au paiement
        foreach ($_POST as $key => $value) {
//            Transforme la valeur dans un format de type $_POST (parce que retransmis quelques lignes plus bas avec le cURL)
            $value = urlencode(stripslashes($value));
            /*$req.= "&$key=$value"; équivaut à $req = $req . "&$key=$value";
            Le & est le séparateur*/
            $req.= "&$key=$value";
        }
        // Envoi des infos a Paypal
//        curl_init initialise une requête
        $fp = curl_init('https://www.sandbox.paypal.com/cgi-bin/webscr');
        curl_setopt($fp, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($fp, CURLOPT_POST, 1);
        curl_setopt($fp, CURLOPT_RETURNTRANSFER,1);
//        La chaine de données du $_POST, autrement dit ce qu'on va transmettre comme données'
        curl_setopt($fp, CURLOPT_POSTFIELDS, $req);
        curl_setopt($fp, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($fp, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($fp, CURLOPT_FORBID_REUSE, 1);
//        Donne le tableau de toutes les entêtes HTTP qu'on veut envoyer'
        curl_setopt($fp, CURLOPT_HTTPHEADER, array('Connection: Close'));
//        curl_exec execute la requête avec toutes les options qu'on vient de donner'
        if( !($res = curl_exec($fp)) ) {
//            curl_close met fin à la requête
            curl_close($fp);
            exit;
            ///todo gérer le cas de l'erreur où on n'a même pas réussi à envoyer la requête à Paypal
        }
        curl_close($fp);
// Le paiement est validé
        if (strcmp(trim($res), "VERIFIED") == 0) {
            // Vérifier que le statut du paiement est complet
            if ($_POST['payment_status'] == "Completed") {
                // Vérification de l'e-mail du vendeur
                if (Utilitaires::EMAIL_VENDEUR == $_POST['receiver_email']) {
                    // Vérification du montant de la commande dans MA BDD
                    /*$req = "SELECT montant_ttc FROM commandes WHERE id=".$_POST['custom'];
                    $rep = mysqli_query($db, $req);
                    $row = mysqli_fetch_array($rep);*/
                    $newCommandeManager = new CommandesManager();
                    try
                    {
                        $newCommandeEntity = $newCommandeManager->getOneCommande($_POST['custom']);
                    }
                    catch (\Exception $e)
                    {
                        ///todo envoyer un mail à l'administrateur pour décrire l'exception
                        exit;
                    }
                    ///todo réécrire les lignes ci-dessus avec PDO et en utilisant mes managers
                    // Vérification de la concordance du montant
                    if ($_POST['mc_gross'] == $newCommandeEntity->montant_total()) {
                        // Requête pour la mise à jour du statut de la commande => Statut à 1
                        // Envoi du mail de récapitulatif de la commande à l'acheteur et au vendeur
                        $newRangFactureCommandeManager = new RangFactureCommandeManager();
                        $newRangFactureCommandeEntity = $newRangFactureCommandeManager->getAndUpdateCurrentNumeroFactureCommande(RangFactureCommandeManager::FACTURE);
                        $numeroFacture = $newRangFactureCommandeEntity->numero_facture();
                        $numeroCommande = $newCommandeEntity->numero_commande();

                        Mailing::sendingEmail('romain.charles@rocketmail.com',
                                                '',
                                                Utilitaires::EMAIL_VENDEUR,
                                                '',
                                                'test email validation commande',
                                                'Merci pour votre commande. Le paiement a bien été reçu');

                        /*
                        $from = "From: " . Utilitaires::EMAIL_VENDEUR;
                        $to = Utilitaires::EMAIL_VENDEUR;
                        $sujet = "Confirmation paiement de la commande" . $numeroCommande;
                        $body = "Merci pour votre commande. Le paiement a bien été effectué.
                                Vous trouverez votre facture numéro" . $numeroFacture . "en pièce-jointe";
                        mail($to, $sujet, $body, $from);
                        */

                        ///todo marche à suivre quand la commande est bien valide (générer numéro de facture etc)
                        /// Possibilité de finir avec un exit;
                    } else {
                        // Envoi d'une alerte par mail (voir modèle en bas de cette section)
                        // Envoi d'un mail au client pour lui dire qu'on ne s'est pas laissé avoir ^^
                        ///todo marche à suivre quand la commande n'est pas valide
                    }
                } else {
                    // Envoi d'une alerte par mail (voir modèle en bas de cette section)
                    // Envoi d'un mail au client pour lui dire qu'on ne s'est pas laissé avoir ^^
                }
            } else {
                // Envoi d'une alerte par mail (voir modèle en bas de cette section)
            }
        } else {
            // Le paiement est invalide, envoi d'un mail au vendeur
            $from = "From: " . Utilitaires::EMAIL_VENDEUR;
            $to = Utilitaires::EMAIL_VENDEUR;
            $sujet = "Paiement invalide";
            $body = $req;
            foreach ($_POST as $key => $value) {
                $text.= $key . " = " .$value ."nn";
            }
            mail($to, $sujet, $text . "nn" . $body, $from);
        }
    }




    /*public function executeRemoveonetoquantity (HTTPRequest $request)
    {
        if (isset($_POST['idPhoto']) && isset($_POST['idDimensions']) && isset($_POST['nombreArticles']))
        {
            try
            {
                $articleId = $_POST['idPhoto'];
                $dimensionsId = $_POST['idDimensions'];
                $nombreArticles = $_POST['nombreArticles'];

                if (!isset($_SESSION['panier']))
                {
                    $_SESSION['panier'] = [];
                }

                //                Le "&" devant la variable $lignePanier indique qu'on veut la référence de l'objet (autrement dit son adresse). Ainsi On peut mettre à jour le tableau en manipulant directement la variable du foreach.
                foreach ($_SESSION['panier'] as &$lignePanier)
                {
                    if ($lignePanier['articleId'] == $articleId && $lignePanier['dimensionsId'] == $dimensionsId && $lignePanier['nombreArticles'] > 0)
                    {
                        $lignePanier['nombreArticles'] -= 1;
//                        Dans la mesure où on ne peut trouver l'élément qu'une seule fois dans le tableau, on stoppe la boucle immédiatement avec le "break"
                        break;
                    }
                }

                echo json_encode(['status'=>'Succès']);
            }
            catch (\Throwable $exception) {
                Utilitaires::logException($exception);
                echo json_encode(['status'=>'Erreur']);
            }
        }
        else
        {
            echo json_encode(['status'=>'Erreur']);
        }

        header('Content-Type: application/json');
        exit;
    }*/




    public function executeAffichagepanier (HTTPRequest $request)
    {
        /*
        var_dump('On est entré dans le panier');
        var_dump($_SESSION['panier']);
        exit;
        */
        $orderedArticles = [];

        foreach ($_SESSION['panier'] as $oneArticle)
        {
            /*
            var_dump($oneArticle);
            exit;
            */

            $newPhotoManager = new PhotosManager();
            $orderedArticle = $newPhotoManager->getOnePhoto($oneArticle["articleId"]);

            $newDimensionsManager = new DimensionsManager();
            $selectedDimensions = $newDimensionsManager->getOneEntryOfDimensions($oneArticle["dimensionsId"]);

            $numberOfArticles = $oneArticle["nombreArticles"];

            $newTarifsManager = new TarifsManager();
            $tarifsEntity = $newTarifsManager->getOnePhotoAndDimensionsTarif($oneArticle["articleId"], $oneArticle["dimensionsId"]);
            $photoTarif = $tarifsEntity->prix();


            $orderedArticles [] = [
                'orderedArticle' => $orderedArticle,
                'selectedDimensions' => $selectedDimensions,
                'numberOfArticles' => $numberOfArticles,
                'photoTarif' => $photoTarif
            ];
        }

        $this->page->addVar('orderedArticles', $orderedArticles);

        /*
        var_dump($orderedArticles);
        exit;
        */

    }
}
/*
$articleId = $_POST['idPhoto'];
$dimensionsId = $_POST['idDimensions'];
$nombreArticles = $_POST['nombreArticles'];

$_SESSION['panier'][] = ['articleId' => $articleId, 'dimensionsId' => $dimensionsId, 'nombreArticles' => $nombreArticles];
*/

/*
array(3) {
    [0]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "1" ["nombreArticles"]=> string(1) "2"
    }
    [1]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "2" ["nombreArticles"]=> string(1) "5"
    }
    [2]=> array(3) {
        ["articleId"]=> string(1) "5" ["dimensionsId"]=> string(1) "2" ["nombreArticles"]=> string(1) "3"
    }
}*/

