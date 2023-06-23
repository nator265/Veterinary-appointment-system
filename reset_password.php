<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'PHPMailer/PHPMailer/src/Exception.php';
    require 'PHPMailer/PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/PHPMailer/src/SMTP.php';

    if(isset($_POST["reset"])){
        $mail = new PHPMailer(true);

        $mail-> isSMTP();
        $mail -> Host = 'smtp.gmail.com';
        $mail ->SMTPAuth = true;
        $mail ->Username = 'Kelvinzimba2322000@gmail.com';
        $mail ->Password = '2875 3394';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail ->Port = 465;
        $mail ->setFrom('Kelvinzimba2322000@gmail.com');

        $mail ->addAddress($_POST['email']);
        $mail->isHTML(true);
        $mail->Subject = "Reset Password";
        $mail->Body = "12345";

        $mail->send();

        echo
        "
        <script>
        alert('sent successfully');
        document.location.href = 'reset.php'
        ";
        header('location.reset2.php');
    }
?>