<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/PHPMailer/src/Exception.php';
require 'lib/PHPMailer/src/PHPMailer.php';
require 'lib/PHPMailer/src/SMTP.php';

$messaggio = new PHPMailer();
$messaggio->isSMTP();
$messaggio->Host = "mixer.unipi.it";
$messaggio->SMTPSecure = "tls";
$messaggio->SMTPAuth = false;
$messaggio->Port = 25;

$messaggio->From = 'no-reply-laureandosi@ing.unipi.it';
$messaggio->addAddress('matteommiii@gmail.com');
$messaggio->subject = "subject_text";
$messaggio->Body = stripslashes("Hello world!");

if (!$messaggio -> send()) {
    echo $messaggio->ErrorInfo;
} else {
    echo "Email inviata correttamente!";
}

$messaggio->smtpClose();
unset($messaggio);
?>
<script type="text/javascript">
    console.log('Pdf Generati');
</script>
