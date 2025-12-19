<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? '';
$password = $data['password'] ?? '';

if (!$id || !$password) {
    echo json_encode(["success" => false, "message" => "Missing ID or password"]);
    exit;
}

$conn = mysqli_connect("localhost", "root", "root", "db");
if (!$conn) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}

$hashedPassword = md5($password);

$sql = "SELECT * FROM users WHERE user_id='$id' AND password='$password'";
$result = mysqli_query($conn, $sql);

if ($result && mysqli_num_rows($result) > 0) {
    echo json_encode(["success" => true, "message" => "Login successful"]);
} else {
    echo json_encode(["success" => false, "message" => "Invalid credentials"]);
}

mysqli_close($conn);
?>