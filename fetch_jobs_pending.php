<?php
session_start();


$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$conn = mysqli_connect($host, $user, $password, $db);
if ($conn === false) {
    die("ERROR: Could not connect. " . mysqli_connect_error());
}


$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($username === 'Guest') {
    echo json_encode([]);
    exit();
}


$sql = "SELECT username,id, job_category, mobile_number, review_rating,email, emp_email, status, created_at, job_title, job_location, job_image, job_date, job_end_date 
        FROM jobs_pendings 
        WHERE poster_username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$jobs = [];

while ($row = mysqli_fetch_assoc($result)) {
    $jobs[] = $row;
}


echo json_encode($jobs);


mysqli_close($conn);
?>
