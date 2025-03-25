<?php

session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['job_id'])) {
    $jobId = $_POST['job_id'];

    
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "job_mart";

    $conn = mysqli_connect($host, $user, $password, $db);

    
    if ($conn === false) {
        die("Connection failed: " . mysqli_connect_error());
    }

    
    $sql = "DELETE FROM job_posting WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
       
        $_SESSION['message'] = "Job post deleted successfully.";
        $_SESSION['msg_type'] = "success"; 
    } else {
        
        $_SESSION['message'] = "Failed to delete the job post.";
        $_SESSION['msg_type'] = "error"; 
    }

   
    $stmt->close();
    $conn->close();

    
    header("Location: Manage_Admin_job.php");
    exit();
}
?>
