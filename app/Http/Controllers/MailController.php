<?php

namespace App\Http\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailController extends Controller
{
    public function sendMail()
    {
        require base_path('vendor/autoload.php'); // Asegúrate de cargar PHPMailer

        $mail = new PHPMailer(true); // Crea una instancia de PHPMailer

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com'; // Servidor SMTP
            $mail->SMTPAuth   = true;
            $mail->Username   = 'helpdesk@grupoprestige.com.mx'; // Tu correo
            $mail->Password   = 'Loma861203ba3'; // Contraseña de aplicación
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Configuración del remitente y destinatario
            $mail->setFrom('abigail.garcia1942@gmail.com', 'Grupo Prestige');
            $mail->addAddress('helpdesk@grupoprestige.com.mx'); // Destinatario

            // Contenido del correo
            $mail->isHTML(true); // Activa formato HTML
            $mail->Subject = 'Prueba de sistema';
            $mail->Body    = 'Notifica alta de candidato';

            // Enviar correo
            $mail->send();
            return "El correo electrónico se ha enviado correctamente.";
        } catch (Exception $e) {
            return "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
        }
    }
}
