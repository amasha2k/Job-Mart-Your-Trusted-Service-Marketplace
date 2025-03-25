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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($username === 'Guest') {
    header("Location: login.php");
    exit();
}


$query = "SELECT created_at, emp_amount FROM payment_progress WHERE emp_username = '$username'";
$result = mysqli_query($conn, $query);

$data = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = [
            'date' => $row['created_at'],
            'amount' => (float)$row['emp_amount']
        ];
    }
}

echo json_encode($data);
?>
