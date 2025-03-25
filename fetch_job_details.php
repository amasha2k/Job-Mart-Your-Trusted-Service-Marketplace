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




if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];

    
    $stmt = $conn->prepare("SELECT nameOnCard, cardNumber, expiryDate, cvv FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $userDetails = $result->fetch_assoc();
        echo json_encode($userDetails); 
    } else {
        echo json_encode(['error' => 'User details not found']);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
