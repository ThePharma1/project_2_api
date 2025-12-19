<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

// Get JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Extract first and last name
$first = trim($data['first_name'] ?? '');
$last  = trim($data['last_name'] ?? '');

if (!$first || !$last) {
    echo json_encode([
        "success" => false,
        "message" => "Missing first or last name"
    ]);
    exit;
}

// Connect to database
$conn = mysqli_connect("localhost", "root", "root", "db");
if (!$conn) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

// Prepare SQL statement
$stmt = $conn->prepare("INSERT INTO students (first_name, last_name) VALUES (?, ?)");
$stmt->bind_param("ss", $first, $last);

// Execute and respond
if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Student added successfully"
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Insert failed: " . $stmt->error
    ]);
}

// Close connections
$stmt->close();
$conn->close();
?>