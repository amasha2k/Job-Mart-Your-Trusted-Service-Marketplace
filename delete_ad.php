<?php
session_start(); 

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . mysqli_connect_error()]));
}


$adId = isset($_POST['id']) ? intval($_POST['id']) : 0;

if ($adId > 0) {
    
    $sql = "DELETE FROM job_posting WHERE id = $adId";
    
    
    if (mysqli_query($conn, $sql)) {
        echo json_encode(['success' => true, 'message' => 'Ad deleted successfully.']);
    } else {
       
        echo json_encode(['success' => false, 'message' => 'Error deleting ad: ' . mysqli_error($conn)]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid ad ID.']);
}

mysqli_close($conn);
?>
