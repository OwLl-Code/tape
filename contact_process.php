<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $secretKey = "6LdYGO8nAAAAAH_UONluo7eBSkQjQaq-T_FL_Vfj"; // Замените на ваш Secret Key
    $response = $_POST["g-recaptcha-response"];
    $remoteIp = $_SERVER["REMOTE_ADDR"];

    $url = "https://www.google.com/recaptcha/api/siteverify";
    $data = array(
        "secret" => $secretKey,
        "response" => $response,
        "remoteip" => $remoteIp
    );

    $options = array(
        "http" => array(
            "header" => "Content-type: application/x-www-form-urlencoded\r\n",
            "method" => "POST",
            "content" => http_build_query($data)
        )
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    $resultJson = json_decode($result);

    if ($resultJson->success) {
        // Получение данных из формы
        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // Ваши дополнительные действия с данными из формы (например, отправка письма)
        
        // Пример отправки письма
        $to = "viken@krov.by";
        $subject = "New Contact Form Submission";
        $message = "Name: $name\nEmail: $email\nMessage: $message";
        $headers = "From: $email";

        mail($to, $subject, $message, $headers);

        echo "Форма успешно отправлена!";
    } else {
        echo "reCAPTCHA verification failed!";
    }
}

?>