<?php
// Assuming you have a database connection established
// Replace the database credentials with your own
$servername = 'localhost';
$username = 'placeprintcorp_asd';
$password = 'asdqwe!';
$dbname = 'placeprintcorp_asd';

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to retrieve tradesman data from the database
$sql = "SELECT name, services, paymentAmount FROM tradesman";
$result = $conn->query($sql);

// Check if there are any results
if ($result->num_rows > 0) {
    $tradesmanData = array();

    // Loop through each row and store the tradesman data
    while ($row = $result->fetch_assoc()) {
        $tradesmanData[] = array(
            'name' => $row['name'],
            'services' => $row['services'],
            'paymentAmount' => $row['paymentAmount']
        );
    }

    // Return the tradesman data as JSON
    header('Content-Type: application/json');
    echo json_encode($tradesmanData);
} else {
    echo "No tradesman data found.";
}

// Close the database connection
$conn->close();
?>
