<?php

// Call: echo "Body" | php -f mail_notification.php -- -sSubj -ttest@lobbywatch.ch sql/zb_delta_20170606.sql sql/ws_parlament_ch_sync_20170601.sql

// require 'PHPMailerAutoload.php';
require_once dirname(__FILE__) . '/vendor/phpmailer/phpmailer/PHPMailerAutoload.php';
require_once dirname(__FILE__) . '/public_html/settings/settings.php';
require_once dirname(__FILE__) . '/public_html/common/utils.php';

global $mail_connection;

$verbose = 0;

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

  $options = getopt('s:v::ht:',array('help'));

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

  if (isset($options['h']) || isset($options['help'])) {
    print("mail_notification [OPTIONS] ATTACHMENT1 ATTACHMENT1
Reads body from stdin.

Parameters:
-s subject      Subject
-t emails       Comma separted email addresses
-v[level]       Verbose, optional level, 1 = default
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
print("Sending to " . implode(", ", $emails) . " ...\n");

if(!$mail->send()) {
    print('Message could not be sent.');
    print('Mailer Error: ' . $mail->ErrorInfo);
} else {
    print('Message has been sent');
}

print("\n");
