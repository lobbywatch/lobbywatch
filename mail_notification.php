<?php

// Call: echo "Body" | php -f mail_notification.php -- -sSubj -ttest@lobbywatch.ch sql/zb_delta_20170606.sql sql/ws_parlament_ch_sync_20170601.sql

// require 'PHPMailerAutoload.php';
require_once dirname(__FILE__) . '/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

global $mail_connection;

$verbose = 0;
$quiet = false;

$mail = new PHPMailer;

// $mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = $mail_connection['host'];  // Specify main and backup SMTP servers
$mail->SMTPAuth = true;                               // Enable SMTP authentication
$mail->Username = $mail_connection['username'];             // SMTP username
$mail->Password = $mail_connection['password']; // SMTP password
$mail->SMTPSecure = $mail_connection['secure'];                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = $mail_connection['port'];                                    // TCP port to connect to

$mail->setFrom('admin@lobbywatch.ch', 'Lobbywatch Script');
// $mail->addReplyTo('info@example.com', 'Information');
$mail->isHTML(true);                                  // Set email format to HTML
$mail->CharSet = 'UTF-8';
// $mail->ContentType = 'text/plain';

// https://stackoverflow.com/questions/24463425/send-mail-in-phpmailer-using-dkim-keys

/*
https://yomotherboard.com/how-to-setup-email-server-dkim-keys/
https://www.xpertdns.com/billing/knowledgebase/1/DomainKeys-or-DKIM.html

openssl genrsa -out phpmailer_dkim.rsa.private 1024

openssl rsa -in phpmailer_dkim.rsa.private -out phpmailer_dkim.rsa.public -pubout -outform PEM

phpmailer._domainkey.lobbywatch.ch TXT "k=rsa; p=MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDHKsuABaXhTg8sk0qUFQam+IGQarZlGiDfOIxGrJ8tP6df9xe7Wi5y1A0DPCEnF78HG/qYZKADwtFz/AMWHYocsickoWP/+Ir+WTJUpmbLa+QdFr5/BXgrdBeoR9CORAmf6gsQvbHOzhCGoC+s435SZQ+drlRFaMuR1YwoQcwQ1QIDAQAB"
*/

$mail->DKIM_domain = 'lobbywatch.ch';
$mail->DKIM_private = 'dkim/phpmailer_dkim.rsa.private';
$mail->DKIM_selector = 'phpmailer';
$mail->DKIM_passphrase = '';
$mail->DKIM_identity = $mail->From;

  $options = getopt('s:v::ht:q',array('help'));

  $argx = 0;

    while (++$argx < $argc && preg_match('/^-/', $argv[$argx])); # (no loop body)

    $arguments = array_slice($argv, $argx);

//   var_dump($options);

  if (isset($options['v'])) {
    if ($options['v']) {
      $verbose = $options['v'];
    } else {
      $verbose = 1;
    }
  }

  if (isset($options['s'])) {
    $mail->Subject = $options['s'];
  }

  if (isset($options['t'])) {
    $emails = explode(',', $options['t']);
    foreach($emails as $email) {
        $mail->addAddress($email);
    }
  }

  if (isset($options['q'])) {
    $quiet = true;
    $verbose = 0;
  }

  if (isset($options['h']) || isset($options['help'])) {
    print("mail_notification [OPTIONS] ATTACHMENT1 ATTACHMENT2 ...
Reads body from stdin.

Options:
-s subject      Subject
-t emails       Comma separted email addresses
-v[level]       Verbose, optional level, 1 = default
-q              Quiet
-h, --help      This help
");
    exit(0);
  }

$body = '';
while($f = fgets(STDIN)){
    $body .= $f;
}
if ($verbose > 0) print("Body:\n" . $body);

// $mail->Body    = "<pre>" . htmlspecialchars($body) . "</pre>";
$mail->Body    = "<pre>$body</pre>";
$mail->AltBody = $body;

foreach($arguments as $argument) {
    $mail->addAttachment($argument);         // Add attachments
}

$emails = [];
foreach($mail->GetToAddresses() as $email) {
    $emails[] = $email[0];
}
if (!$quiet) print("Sending to " . implode(", ", $emails) . " ...\n");

if(!$mail->send()) {
    print('Message could not be sent.');
    print('Mailer Error: ' . $mail->ErrorInfo);
    exit(1);
} else if (!$quiet) {
    print('Message has been sent');
}

print("\n");
