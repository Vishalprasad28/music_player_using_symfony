<?php 
namespace App\UserServices;

use FFI\Exception;
use PHPMailer\PHPMailer\PHPMailer;

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/.env');

trait Mailer {
  private function sendMail(string $subject, string $body) {
    $mail = new PHPMailer(TRUE);
    try {
      //Server settings.
      $mail->SMTPDebug  = 0;
      $mail->isSMTP();
      $mail->Host       = 'smtp.gmail.com';
      $mail->SMTPAuth   = TRUE;
      $mail->Username   = $_ENV['EMAIL'];
      $mail->Password   = $_ENV['EMAIL_PASSWORD'];   
      $mail->SMTPSecure = 'tsl';
      $mail->Port       = 587;

      //Recipients.
      $mail->setFrom($_ENV['EMAIL'], 'Vishal');
      //Add a recipient
      $mail->addAddress($this->email);
      $mail->addReplyTo($_ENV['EMAIL']);

      //Content.
      //Set email format to HTML.
      $mail->isHTML(TRUE);
      $mail->Subject = $subject;
      $mail->Body    = $body;

      $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
      $mail->send();
      return TRUE;
    }
    catch (Exception $e) {
      return FALSE;
    }
  }
}