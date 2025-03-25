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
    $ads = []; 

   
    $sql = "SELECT * FROM job_posting WHERE username = '$username' AND status = 'approved'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $ads[] = $row; 
        }
    }

    
    echo json_encode($ads);
} else {
    
    echo json_encode([]);
}

mysqli_close($conn);
?>
