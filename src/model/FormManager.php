<?php

namespace App\Model;

class FormManager
{

    public function fromTraiment($nom, $prenom, $email, $message)
    {
        $entetemail = "From: Blog Projet OpenClassroom <julienroquai@gmail.com>\r\n";
        $entetemail .= "Reply-To: julienroquai@gmail.com\n";
        $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n";
        $entetemail .= "Content-Type: text/plain; charset=utf8\r\n";
        $objet = "Comfirmation de la récption de votre message";
        $message_email = "Nous avons bien reçu votre message";

        mail($email, $objet, $message_email, $entetemail);

        $entetemail = "From:" . $nom . " " . $prenom . " <" . $email . ">\r\n";
        $entetemail .= "Reply-To: " . $email . "\n";
        $entetemail .= "X-Mailer: PHP/" . phpversion() . "\n";
        $entetemail .= "Content-Type: text/plain; charset=utf8\r\n";
        $objet = "Contact depuis votre Blog Boblebicoleur";
        $message_email = $message;

        mail('julienroquai@gmail.com', $objet, $message_email, $entetemail);

    }
}
