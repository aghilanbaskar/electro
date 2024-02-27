<?php

header("Content-Type: application/json");

// Include the Database class
require_once("path/to/Database.php"); // Update with the correct path

// Include the JWT helper file
require_once("path/to/jwt_helper.php"); // Update with the correct path

// Start or resume a session
session_start();

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Get POST data
    $email = $_POST["email"] ?? '';
    $password = $_POST["password"] ?? '';

    // Validate input
    if (empty($email) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Email and password are required"]);
        exit;
    }

    // Create a new Database instance
    $db = new Database();

    // Check if the email exists
    $checkQuery = "SELECT * FROM users WHERE email = '$email'";
    $checkResult = $db->query($checkQuery);

    if ($checkResult->num_rows === 0) {
        $db->close();
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }

    // Fetch user data
    $user = $checkResult->fetch_assoc();
    $hashedPassword = $user["password"];

    // Verify the password using password_verify()
    if (password_verify($password, $hashedPassword)) {
        // Password is correct, set session and generate JWT
        $_SESSION["user_id"] = $user["user_id"];
        $_SESSION["email"] = $user["email"];

        // Example usage of generate_jwt function
        $headers = ["alg" => "HS256", "typ" => "JWT"];
        $payload = ["user_id" => $user["user_id"], "email" => $user["email"]];
        $secret = "your_secret_key"; // Replace with your actual secret key

        $jwtToken = generate_jwt($headers, $payload, $secret);

        $db->close();
        echo json_encode(["success" => true, "message" => "Login successful", "token" => $jwtToken]);
        exit;
    } else {
        // Password is incorrect
        $db->close();
        echo json_encode(["success" => false, "message" => "Incorrect password"]);
        exit;
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

?>
