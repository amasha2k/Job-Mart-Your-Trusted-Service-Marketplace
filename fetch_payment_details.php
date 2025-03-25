<?php
session_start();


$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die(json_encode(['message' => "Connection failed: " . mysqli_connect_error()]));
}


$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($username === 'Guest') {
    echo json_encode(['message' => 'User not logged in']);
    exit();
}


$sql = "SELECT nameOnCard, cardNumber, expiryDate, cvv FROM job_posting WHERE username = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(['message' => 'Failed to prepare SQL statement']));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $paymentDetails = $result->fetch_assoc(); 
    echo json_encode($paymentDetails);
} else {
    echo json_encode(['message' => 'No payment details found for this user']);
}

$stmt->close();
$conn->close();
?>
