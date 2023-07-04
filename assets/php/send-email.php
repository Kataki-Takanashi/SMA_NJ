$to = 'alibugrahim06@gmail.com';
$subject = 'Files from file drop';
$message = 'Here are the files sent from the file drop:';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $files = $_FILES['file'];

  foreach ($files['tmp_name'] as $index => $tmpName) {
    $fileName = $files['name'][$index];
    $fileSize = $files['size'][$index];
    $fileType = $files['type'][$index];
    $fileError = $files['error'][$index];

    if ($fileError === UPLOAD_ERR_OK) {
      $fileContent = file_get_contents($tmpName);
      $fileContent = chunk_split(base64_encode($fileContent));

      $boundary = md5(time());
      $headers = "From: alibugrahim06@gmail.com\r\n"
        . "MIME-Version: 1.0\r\n"
        . "Content-Type: multipart/mixed; boundary=\"" . $boundary . "\"\r\n"
        . "X-Mailer: PHP/" . phpversion();

      $body = "--" . $boundary . "\r\n"
        . "Content-Type: text/plain; charset=\"iso-8859-1\"\r\n"
        . "Content-Transfer-Encoding: 8bit\r\n\r\n"
        . $message . "\r\n\r\n"
        . "--" . $boundary . "\r\n"
        . "Content-Type: " . $fileType . "; name=\"" . $fileName . "\"\r\n"
        . "Content-Transfer-Encoding: base64\r\n"
        . "Content-Disposition: attachment; filename=\"" . $fileName . "\"\r\n\r\n"
        . $fileContent . "\r\n\r\n"
        . "--" . $boundary . "--";

      if (mail($to, $subject, $body, $headers)) {
        // Email sent successfully
        // You can return a success response to the client if needed
      } else {
        // Error sending email
        // You can return an error response to the client if needed
      }
    }
  }
}