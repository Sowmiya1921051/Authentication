<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
session_start();

// Include the database connection
include 'db.php'; 

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data (email/phone and password)
    $data = json_decode(file_get_contents("php://input"), true);
    $emailOrPhone = $data['emailOrPhone'] ?? '';
    $password = $data['password'] ?? '';

    // Validate input
    if (!$emailOrPhone || !$password) {
        echo json_encode(["success" => false, "message" => "Both fields are required"]);
        exit();
    }

    // Check if the email or phone exists in the database
    $query = $con->prepare("SELECT * FROM register WHERE (email = ? OR phone = ?) LIMIT 1");
    $query->bind_param("ss", $emailOrPhone, $emailOrPhone);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 0) {
        echo json_encode(["success" => false, "message" => "User not found"]);
    } else {
        // Get user data
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password'])) {
            // Set session or token if necessary
            $_SESSION['user_id'] = $user['id'];
            echo json_encode(["success" => true, "message" => "Login successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password"]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
