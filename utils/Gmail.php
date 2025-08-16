<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

/**
 * Invia un'email tramite Gmail SMTP
 * @param string $subject   Oggetto dell'email
 * @param string $htmlBody  Corpo HTML dell'email
 * @return bool             true se invio OK, false se errore
 */
function sendOtpWithGmail($htmlBody, $toEmail) {
    $mail = new PHPMailer(true);
    try {
        // Configurazione del server SMTP di Google
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['GMAIL_USERNAME']; // Inserire la tua email Gmail
        $mail->Password = $_ENV['GMAIL_PASSWORD']; // Inserire la password o App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Impostazioni email
        $mail->setFrom($_ENV['GMAIL_USERNAME'], 'mittente');
        $mail->addAddress($toEmail, 'destinatario');
        
        // Contenuto
        $mail->isHTML(true);
        $mail->Subject = 'Codice OTP gmail smtp - Gestore-eventi';
        $mail->Body = $htmlBody;

        // Invia l'email
        return $mail->send();
    } catch (Exception $e) {
        error_log('Errore invio email con Gmail: ' . $mail->ErrorInfo);
        return false;
    }
}