<?php
declare(strict_types=1);

namespace Perfumeria\Control;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Asegúrate de que la ruta a config sea correcta
require_once __DIR__ . '/../config/config.php';

class Correo {
    
    /**
     * Envía un correo electrónico vía SMTP
     */
    public function enviar(string $destinatario, string $nombreDestinatario, string $asunto, string $cuerpoHtml): bool {
        $mail = new PHPMailer(true);

        try {
            // 1. Configuración del Servidor
            // $mail->SMTPDebug = 2; // Descomentar para ver errores en pantalla si no funciona
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USER;
            $mail->Password   = SMTP_PASS;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;
            $mail->CharSet    = 'UTF-8'; // Para ñ y acentos

            // 2. Remitente y Destinatario
            $mail->setFrom(EMAIL_REMITENTE, NOMBRE_REMITENTE);
            $mail->addAddress($destinatario, $nombreDestinatario);

            // 3. Contenido
            $mail->isHTML(true);
            $mail->Subject = $asunto;
            $mail->Body    = $cuerpoHtml;
            // Versión texto plano para clientes de correo antiguos
            $mail->AltBody = strip_tags($cuerpoHtml); 

            $mail->send();
            return true;

        } catch (Exception $e) {
            // En producción, aquí guardarías el error en un log: $mail->ErrorInfo
            return false;
        }
    }
}