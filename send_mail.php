<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require "PHPMailer/src/Exception.php";
require "PHPMailer/src/PHPMailer.php";

$response = ["message" => ""];

if (isset($_POST['g-recaptcha-response']) && $_POST['g-recaptcha-response']) {
    $secret = '6LdYGO8nAAAAAH_UONluo7eBSkQjQaq-T_FL_Vfj';
    $ip = $_SERVER['REMOTE_ADDR'];
    $response_captcha = $_POST['g-recaptcha-response'];
    $rsp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response_captcha&remoteip=$ip");
    $arr = json_decode($rsp, TRUE);

    if (!$arr['success']) {
        $response['message'] = "Invalid captcha";
    } else {
        try {
            $mail = new PHPMailer(true);
            $mail->CharSet = "UTF-8";
            $mail->IsHTML(true);

            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $message = $_POST["message"];
            $email_template = "template_mail.html";

            $body = file_get_contents($email_template);
            $body = str_replace('%name%', $name, $body);
            $body = str_replace('%email%', $email, $body);
            $body = str_replace('%phone%', $phone, $body);
            $body = str_replace('%message%', $message, $body);

      $mail->addAddress("viken@krov.by"); 
             // Add more email addresses here
            // You can also add multiple recipients by calling addAddress() multiple times

            $mail->setFrom($email);
            $mail->Subject = "[сообщение с формы сайта dahimport.by]";
            $mail->MsgHTML($body);
            $mail->send();
            
            $response['message'] = "Сообщение отправлено";
        } catch (Exception $e) {
            $response['message'] = "Ошибка: " . $mail->ErrorInfo;
        }
    }
} else {
    $response['message'] = "Не удалось выполнить проверку Captcha";
}

header('Content-type: application/json');
echo json_encode($response);
?>









