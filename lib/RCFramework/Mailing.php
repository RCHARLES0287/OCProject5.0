<?php

namespace RCFramework;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

abstract class Mailing
{
    public static function sendingEmail ($emailDestinataire, $emailEnCopie, $emailEnCopieCachee, $attachement, $subject, $emailBody)
    {
        // Passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try
        {
            Utilitaires::logMessage("Paramétrage SMTP");
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_OFF;
            $mail->isSMTP();
            $mail->Host       = 'smtp.mail.yahoo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = Utilitaires::EMAIL_VENDEUR_TEST;
            $mail->Password   = file_get_contents(__DIR__ .'/../../.env');
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->CharSet    = PHPMailer::CHARSET_UTF8;

            //Recipients
            Utilitaires::logMessage("Construction mail");
            $mail->setFrom(Utilitaires::EMAIL_VENDEUR_TEST);
            $mail->addAddress($emailDestinataire);
//            $mail->addAddress(Utilitaires::EMAIL_VENDEUR_TEST);
//            $mail->addReplyTo('info@example.com', 'Information');
            if (!Utilitaires::emptyMinusZero($emailEnCopie))
            {
                $mail->addCC($emailEnCopie);
            }
            if (!Utilitaires::emptyMinusZero($emailEnCopieCachee))
            {
                $mail->addBCC($emailEnCopieCachee);
            }


            //Attachments
            if (!Utilitaires::emptyMinusZero($attachement))
            {
                $mail->addAttachment($attachement);
            }


            //Content
            //Set email format to HTML
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $emailBody;
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            Utilitaires::logMessage("Envoi du mail prêt");
            $reponseMailing = $mail->send();
            Utilitaires::logMessage("Résultat de l'envoi du mail : ");
            Utilitaires::logMessage($reponseMailing);
//            echo 'Message has been sent';
        }
        catch (Exception $e)
        {
//            Utilitaires::logMessage("Bla bla bla");
            Utilitaires::logException($e);
//            Utilitaires::logMessage("Erreur dans l'envoi du mail " . $mail->ErrorInfo);
        }
//        Utilitaires::logMessage("Bla bla bla bla bla bla");

    }
}