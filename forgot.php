<?php
//dont forget na ilgay to sa top lage
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);                              
?>


<?php 
/* Reset your password form, sends reset.php password link */
require 'db.php';
session_start();


if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) 
{   
    $email = $mysqli->escape_string($_POST['email']);
    $result = $mysqli->query("SELECT * FROM users WHERE email='$email'");

    if ( $result->num_rows == 0 ) 
    { 
        $_SESSION['message'] = "User with that email doesn't exist!";
        header("location: error.php");
    }
    else { 

        $user = $result->fetch_assoc(); 
        
        $email = $user['email'];
        $hash = $user['hash'];
        $first_name = $user['first_name'];

      
        $_SESSION['message'] = "<p>Please check your email <span>$email</span>"
        . " for a confirmation link to complete your password reset!</p>";

       
        $to      = $email;
        $subject = 'Password Reset Link ( maangeloatienza@gmail.com )';
        $message_body = '
        Hello '.$first_name.',

        You have requested password reset!

        Please click this link to reset your password:

        http://localhost/login-system/reset.php?email='.$email.'&hash='.$hash;


// ito yung need to send email
    $mail->isSMTP(); 
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = ''; // lagay mo email mo
    $mail->Password = ''; // password ng email
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('maangeloatienza@gmail.com', 'GELO');
    $mail->addAddress($to, $first_name); 
    $mail->addReplyTo('maangeloatienza@gmail.com', 'Information');
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $message_body;
    $mail->send();
    header("location: success.php");
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Reset Your Password</title>
  <?php include 'css/css.html'; ?>
</head>

<body>
    
  <div class="form">

    <h1>Reset Your Password</h1>

    <form action="forgot.php" method="post">
     <div class="field-wrap">
      <label>
        Email Address<span class="req">*</span>
      </label>
      <input type="email"required autocomplete="off" name="email"/>
    </div>
    <button class="button button-block"/>Reset</button>
    </form>
  </div>
          
<script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
<script src="js/index.js"></script>
</body>

</html>
