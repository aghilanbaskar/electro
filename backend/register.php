<?php

header("Content-Type: application/json");

// Include the Database class
require_once("path/to/Database.php"); // Update with the correct path

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data
    $fullName = $_POST["fullName"] ?? '';
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';
    // Add more fields as needed

    // Validate input (you may want to add more validation)
    if (empty($fullName) || empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Full name, email, and password are required"]);
        exit;
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format"]);
        exit;
    }

    // Create a new Database instance
    $db = new Database();

    // Check if the email already exists
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = $db->query($checkQuery);

    if ($checkResult->num_rows > 0) {
        $db->close();
        echo json_encode(["success" => false, "message" => "Email already exists"]);
        exit;
    }

    // Hash the password using password_hash()
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user into the database
    $insertQuery = "INSERT INTO users (full_name, email, password) VALUES ('$fullName', '$email', '$hashedPassword')";
    $insertResult = $db->query($insertQuery);

    if ($insertResult) {
        $userId = $db->getLastInsertedId();
        $db->close();
        echo json_encode(["success" => true, "message" => "User registered successfully", "user_id" => $userId]);
        exit;
    } else {
        $db->close();
        echo json_encode(["success" => false, "message" => "Registration failed"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

?>
