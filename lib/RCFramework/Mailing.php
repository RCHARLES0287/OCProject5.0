<?php

namespace RCFramework;

use Exception;
use PHPMailer\PHPMailer\PHPMailer;

abstract class Mailing
{
    public static function sendingEmail ($emailDestinataire, $emailEnCopie, $emailEnCopieCachee, $attachement, $subject, $emailBody)
    {
        // Passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try
        {
            //Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->isSMTP();
            $mail->Host       = 'mail.yahoo.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = Utilitaires::EMAIL_VENDEUR;
            $mail->Password   = Utilitaires::MDP_EMAIL_VENDEUR;
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            //Recipients
            $mail->setFrom(Utilitaires::EMAIL_VENDEUR);
            $mail->addAddress($emailDestinataire);
//            $mail->addReplyTo('info@example.com', 'Information');
            $mail->addCC($emailEnCopie);
            $mail->addBCC($emailEnCopieCachee);

            //Attachments
            $mail->addAttachment($attachement);

            //Content
            //Set email format to HTML
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $emailBody;
//            $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

            $mail->send();
//            echo 'Message has been sent';
        }
        catch (Exception $e)
        {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    }
}