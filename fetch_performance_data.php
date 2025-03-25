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

$username = isset($_GET['username']) ? mysqli_real_escape_string($conn, $_GET['username']) : '';


$query = "SELECT emp_amount, created_at FROM payment_progress WHERE emp_username = '$username' ORDER BY created_at ASC";
$result = mysqli_query($conn, $query);

$performanceData = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $performanceData[] = $row; 
    }
}

mysqli_close($conn);


header('Content-Type: application/json');
echo json_encode($performanceData);
?>
