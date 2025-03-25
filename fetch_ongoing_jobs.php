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

$username = $_SESSION['username'];


$sql = "SELECT * FROM jobs_pendings WHERE username = ? AND total_amount IS NOT NULL AND total_amount > 0";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;  
}

echo json_encode($jobs);

$stmt->close();
$conn->close();
?>
