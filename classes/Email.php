<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    protected $email;
    protected $name;
    protected $token;

    public function __construct($name, $email, $token) {
        $this -> email = $email;
        $this -> name = $name;
        $this -> token = $token;
    }

    public function sendEmail() {
        
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'bcef7b412e58da';
        $mail->Password = '5fa5d8ad9a8bf6';

        $mail->setFrom('sinco.support@codestokes.com');
        $mail->addAddress('sinco.support@codestrokes.com', 'sinco.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        
        $content = "<html>";
        $content .= "<p>Hola " .  $this->name . ",</p>";
        $content .= "<p>Para confirmar tu cuenta, haz clic en el siguiente enlace:</p>";
        $content .= "<p><a href='http://localhost:3000/confirm?token=" . $this->token . "'>Confirmar cuenta</a></p>";
        $content .= "</html>";

        $mail->Body = $content;

        // Send the email
        $mail->send();
    }

    public function sendPasswordReset() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'bcef7b412e58da';
        $mail->Password = '5fa5d8ad9a8bf6';

        $mail->setFrom('sinco.support@codestokes.com');
        $mail->addAddress('sinco.support@codestrokes.com', 'sinco.com');
        $mail->Subject = 'Actualiza tu contraseña';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        $content = "<html>";
        $content .= "<p>Hola, recibimos una petición para restablecer la contraseña del correo: " .  $this->name . "</p>";
        $content .= "<p>Para restablecer tu contraseña, haz clic en el siguiente enlace:</p>";
        $content .= "<p><a href='http://localhost:3000/reset?token=" . $this->token . "'>Restablecer contraseña</a></p>";

        $mail->Body = $content;

        // Send the email
        $mail->send();

    }
}