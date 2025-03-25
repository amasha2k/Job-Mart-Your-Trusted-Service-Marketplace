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


$sql = "SELECT * FROM jobs_pendings WHERE poster_username = ? AND job_done IS NOT NULL AND job_done != 0";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die(json_encode(['message' => 'Failed to prepare SQL statement']));
}

$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;  
}


if (count($jobs) > 0) {
    echo json_encode($jobs);
} else {
    echo json_encode(['message' => 'No jobs found for payment']);
}

$stmt->close();
$conn->close();
?>
