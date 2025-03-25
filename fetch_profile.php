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

if ($username !== 'Guest') {
    
    $sql = "SELECT * FROM users WHERE username = '$username' LIMIT 1";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result); 
    }

    
    echo json_encode($user);
} else {
    
    echo json_encode([]);
}

mysqli_close($conn);
?>
