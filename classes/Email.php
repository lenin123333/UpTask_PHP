<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;


class Email
{
    public $email;
    public $nombre;
    public $token;

    public function __construct($email, $nombre, $token)
    {

        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion()
    {

        //Crear el onjeto de email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = '587';
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'tls';
        $mail->Username = 'lenin123333@gmail.com';
        $mail->Password = 'jpbesnwaxqxmryfd';

        $mail->setFrom('lenin123333@gmail.com','Lenin Hernandez');
        $email = $this->email;
        $mail->addAddress($email, 'UpTask.com');
        $mail->Subject = 'Confirma tu cuenta';
        //Set Html
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $contenido = "<html>";
        $contenido .= "<p><strong>Hola". $this->nombre. "<strong> Has creado tu cuenta
        en UpTask, solo debes confirmarla el siguente enlace</p>";
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirmar?token=".$this->token."'> Confirmar cuenta <a/> </p>";
        $contenido .= "<p>Si tu no solitaste esta cuenta ignora el mensaje</p>";
        $contenido .="</html>";
        $mail->Body = $contenido;
        //Enviar el email
        $mail->send();
        
    }

    public function enviarInstrucciones(){
         //Crear el onjeto de email
         $mail = new PHPMailer();
         $mail->isSMTP();
         $mail->Host = 'smtp.gmail.com';
         $mail->Port = '587';
         $mail->SMTPAuth = true;
         $mail->SMTPSecure = 'tls';
         $mail->Username = 'lenin123333@gmail.com';
         $mail->Password = 'jpbesnwaxqxmryfd';
 
         $mail->setFrom('lenin123333@gmail.com','Lenin Hernandez');
         $email = $this->email;
         $mail->addAddress($email, 'UpTask.com');
         $mail->Subject = 'Restablecer tu password';
         //Set Html
         $mail->isHTML(true);
         $mail->CharSet = 'UTF-8';
         $contenido = "<html>";
         $contenido .= "<p><strong>Hola". $this->nombre. "<strong>Has solicitado restablecer tu password, sigue el siguiente enlace para hacerlo </p>";
         $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/restablecer?token=".$this->token."'> restablecer contraseña<a/> </p>";
         $contenido .= "<p>Si tu no solitaste esta cuenta ignora el mensaje</p>";
         $contenido .="</html>";
         $mail->Body = $contenido;
         //Enviar el email
         $mail->send();
         
    }
}