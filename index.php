<?php
require_once __DIR__ . '/mpdf/vendor/autoload.php';

require("email-receipt.php");
$mpdf = new \Mpdf\Mpdf(); // New PDF object with encoding & page size
$mpdf->setAutoTopMargin = 'stretch'; // Set pdf top margin to stretch to avoid content overlapping
$mpdf->setAutoBottomMargin = 'stretch'; // Set pdf bottom margin to stretch to avoid content overlapping
$mpdf->WriteHTML($pdfcontents); // Writing html to pdf
// FOR EMAIL
$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email 
$content = chunk_split(base64_encode($content));
// email stuff (change data below)
$mail_to = "receiver-email"; 
$from_mail = "sender-email";
$replyto = "reply-to";
$from_name = "sender-name"; 
$subject = "Subject - Thanks for support!"; 
$msg = "<h1>whatever HTML body you want to add above the attachment.</h1>";
$separator = md5(time());
$filename = "rizwanrn.pdf";
$uid = md5(uniqid(time()));

$eol = PHP_EOL;
// Basic headers
$header = "From: ".$from_name." <".$from_mail.">".$eol;
$header .= "Reply-To: ".$replyto.$eol;
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"";

// Put everything else in $message
$message = "--".$uid.$eol;
$message .= "Content-Type: text/html;charset=UTF-8".$eol;
$message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
$message .= $msg.$eol;
$message .= "--".$uid.$eol;
$message .= "Content-Type: multipart/mixed; name=\"".$filename."\"".$eol;
$message .= "Content-Transfer-Encoding: base64".$eol;
$message .= "Content-Disposition: attachment; filename=\"".$filename."\"".$eol;
$message .= $content.$eol;
$message .= "--".$uid."--";
mail($mail_to, $subject, $message, $header);
