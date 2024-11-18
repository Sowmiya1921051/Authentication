<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');
session_start();

// Include the database connection
include 'db.php'; 

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the input data (name, email, phone, password, confirmPassword)
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';  // Added phone field
    $password = $data['password'] ?? '';
    $confirmPassword = $data['confirmPassword'] ?? '';  // Added confirmPassword field

    // Validate input
    if (!$name || !$email || !$phone || !$password || !$confirmPassword) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }

    // Check if the passwords match
    if ($password !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Passwords do not match"]);
        exit();
    }

    // Check if the email or phone already exists in the database
    $query = $con->prepare("SELECT * FROM register WHERE email = ? OR phone = ? LIMIT 1");
    $query->bind_param("ss", $email, $phone);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Email or Phone number already registered"]);
    } else {
        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Insert the new user into the database
        $insertQuery = $con->prepare("INSERT INTO register (name, email, phone, password) VALUES (?, ?, ?, ?)");
        $insertQuery->bind_param("ssss", $name, $email, $phone, $hashedPassword);

        if ($insertQuery->execute()) {
            echo json_encode(["success" => true, "message" => "Registration successful"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to register user"]);
        }
    }
} 

// Check for GET request to fetch all users
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all user details from the database
    $query = $con->prepare("SELECT * FROM register");
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        echo json_encode(["success" => true, "users" => $users]);
    } else {
        echo json_encode(["success" => false, "message" => "No users found"]);
    }
} 

else {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
?>
