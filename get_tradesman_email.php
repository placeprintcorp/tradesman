<?php
// Assuming you have a database connection, you can retrieve the tradesman email based on the tradesmanId
$servername = 'localhost';
$username = 'placeprintcorp_asd';
$password = 'asdqwe!';
$dbname = 'placeprintcorp_asd';

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the tradesmanId from the AJAX request
$tradesmanId = $_POST['tradesmanId'];

// Prepare the SQL statement using a prepared statement
$stmt = $conn->prepare("SELECT email FROM tradesman WHERE tradesmanId = ?");
$stmt->bind_param("i", $tradesmanId);

// Execute the prepared statement
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();

// Check if a row was found
if ($result->num_rows > 0) {
    // Fetch the email from the result
    $row = $result->fetch_assoc();
    $email = $row['email'];

    // Prepare the response as a JSON object
    $response = array('success' => true, 'email' => $email);
} else {
    // No tradesman found with the given tradesmanId
    $response = array('success' => false);
}

// Close the prepared statement and database connection
$stmt->close();
$conn->close();

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
