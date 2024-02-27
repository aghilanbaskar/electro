<?php

header("Content-Type: application/json");

// Include the Database class
require_once("path/to/Database.php"); // Update with the correct path

// Include the JWT helper file
require_once("path/to/jwt_helper.php"); // Update with the correct path

// Start or resume a session
session_start();

// Check if the request method is GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Check if the Authorization header is present
    if (!isset($_SERVER['HTTP_AUTHORIZATION'])) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized - Token not provided"]);
        exit;
    }

    // Extract the JWT from the Authorization header
    $authorizationHeader = $_SERVER['HTTP_AUTHORIZATION'];
    list($jwtToken) = sscanf($authorizationHeader, 'Bearer %s');

    if (!$jwtToken) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized - Invalid token format"]);
        exit;
    }

    // Verify the JWT
    $secret = "your_secret_key"; // Replace with your actual secret key
    $decodedToken = jwt_decode($jwtToken, $secret);

    if (!$decodedToken) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Unauthorized - Invalid token"]);
        exit;
    }

    // At this point, the JWT is valid
    $userId = $decodedToken->user_id;

    // Fetch user profile from the database
    $db = new Database();
    $profileQuery = "SELECT * FROM users WHERE user_id = '$userId'";
    $profileResult = $db->query($profileQuery);

    if ($profileResult->num_rows === 0) {
        $db->close();
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "User not found"]);
        exit;
    }

    $userProfile = $profileResult->fetch_assoc();
    $db->close();

    // Respond with the user profile
    echo json_encode(["success" => true, "user_profile" => $userProfile]);
    exit;
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit;
}
