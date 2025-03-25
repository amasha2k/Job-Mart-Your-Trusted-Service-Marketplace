<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

if ($username === null) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}


$query = "SELECT * FROM payment_progress WHERE emp_username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$payments = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
    echo json_encode(['status' => 'success', 'data' => $payments]);
} else {
    echo json_encode(['status' => 'success', 'data' => []]); 
}

$stmt->close();
$conn->close();
?>
