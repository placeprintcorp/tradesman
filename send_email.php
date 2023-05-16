<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
  // Retrieve the POST parameters
  $email = $_POST['email'];
  $amount = $_POST['amount'];
  $services = $_POST['services'];

  // Get the client's IP address
  if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  } else {
    $ip = $_SERVER['REMOTE_ADDR'];
  }

  // Compose the email content
  $subject = "Tradesman Hired";
  $message = "Congratulations! You have been hired.\n";
  $message .= "Client IP: " . $ip . "\n";
  $message .= "Amount: " . $amount . "\n";
  $message .= "Services: " . $services . "\n";

  // Set the recipient email address
  $to = $email;

  // Set additional headers
  $headers = "From: support@placeprintcorp.com\r\n";
  $headers .= "Reply-To: support@placeprintcorp.com\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

  // Send the email
  $success = mail($to, $subject, $message, $headers);

  if ($success) {
    echo "Email sent successfully";
  } else {
    echo "An error occurred while sending the email";
  }
} else {
  echo "Invalid request";
}
?>
