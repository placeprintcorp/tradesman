<?php
$name = $_POST['name'];
$services = $_POST['services'];
$paymentAmount = $_POST['paymentAmount'];
$email = $_POST['email'];

// Perform database operations here

// Assuming you have a database connection, you can insert the data into a table
// Example using MySQLi:
$servername = 'localhost';
$username = 'placeprintcorp_asd';
$password = 'asdqwe!';
$dbname = 'placeprintcorp_asd';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the SQL statement using a prepared statement
$stmt = $conn->prepare("INSERT INTO tradesman (name, services, paymentAmount, email) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $services, $paymentAmount, $email);

// Execute the prepared statement
if ($stmt->execute()) {
    echo "Tradesman data inserted successfully.";

    // Send an email to the tradesman
    $to = $email;
    $subject = "Tradesman Hire";
    $message = "Dear Tradesman,\n\nYou have been hired!\n\nName: $name\nServices: $services\nPayment Amount: $paymentAmount";
    $headers = "From: support@placeprintcoporation.com"; // Replace with your own email address

    // Send the email
    mail($to, $subject, $message, $headers);
} else {
    echo "Error: " . $stmt->error;
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();
?>
