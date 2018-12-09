<?php

if(isset($_POST["info"])) {

  $method = $_SERVER['REQUEST_METHOD'];

  $project_name = "Rmedia";
  $admin_email  = "info@rmedia.kz, client@marketing-time.kz";
  $server_mail = "<info@rmedia.kz>";
  $form_subject = "Заявка";


  //Script Foreach
  $c = true;
  if ( $method === 'POST' ) {

    foreach ( $_POST as $key => $value ) {
      if ( $value != "") {
        if (is_array($value)) {
            $value = implode(',', $value);
        }
        $message .= "
        " . ( ($c = !$c) ? '<tr>':'<tr style="background-color: #f8f8f8;">' ) . "
          <td style='padding: 10px; border: #e9e9e9 1px solid;'><b>$key</b></td>
          <td style='padding: 10px; border: #e9e9e9 1px solid;'>$value</td>
        </tr>
        ";
      }
    }
  }

  $message = "<table style='width: 100%;'>$message</table>";

  function adopt($text) {
    return '=?UTF-8?B?'.Base64_encode($text).'?=';
  }

  $headers = 'From: '.$project_name.' '.$server_mail. PHP_EOL .
            'Reply-To: '.$admin_email.'' . PHP_EOL;
            'MIME-Version: 1.0' . PHP_EOL ;

  if (isset($_FILES["file"])) {

    $file_name = $_FILES["file"]["name"];
    $file_content = chunk_split(base64_encode(file_get_contents($_FILES["file"]["tmp_name"])));
    $uid = md5(uniqid(time()));

    // message & attachment
    $file_message = "--".$uid."\r\n";
    $file_message .= "Content-type:text/html; charset=utf-8\r\n";
    $file_message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $file_message .= $message . "\r\n";
    $file_message .= "--".$uid."\r\n";
    $file_message .= "Content-Type: application/octet-stream; name=\"".$file_name."\"\r\n";
    $file_message .= "Content-Transfer-Encoding: base64\r\n";
    $file_message .= "Content-Disposition: attachment; filename=\"".$file_name."\"\r\n\r\n";
    $file_message .= $file_content."\r\n\r\n";
    $file_message .= "--".$uid."--";

    $message = $file_message;
    $headers .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"" . PHP_EOL ;
  }
  else {
    $headers .= "Content-Type: text/html; charset=utf-8" . PHP_EOL ;
  }

  mail($admin_email, adopt($form_subject), $message, $headers);

  header("Location: /thanks.html");


  
} 
