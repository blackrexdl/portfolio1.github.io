<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$db_host = 'localhost';
$db_username = 'root'; // Database username
$db_password = ''; // No password or set to 'root' if required
$db_name = 'Po'; // Database name

// Create a connection to the database
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$form_data = $_POST;

if (!empty($form_data)) {
    // Validate and sanitize form data
    $fullname = htmlspecialchars($form_data['fullname']);
    $email = filter_var($form_data['email'], FILTER_SANITIZE_EMAIL); // Sanitize email
    $message = htmlspecialchars($form_data['message']);

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO message (fullname, email, message) VALUES (?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    
    $stmt->bind_param("sss", $fullname, $email, $message); // "sss" means 3 string parameters

    // Execute the prepared statement
    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }

    // Close the prepared statement
    $stmt->close();
} else {
    echo "<script>alert('No form data received.');</script>";
}

// Close the database connection
$conn->close();
?>