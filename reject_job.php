<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $jobId = $_POST['id'];

    
    $sql = "UPDATE jobs_pendings SET status = 'Rejected' WHERE id = ?";
    
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jobId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "Job rejected successfully.";
        } else {
            echo "Failed to reject the job. Please try again.";
        }

        $stmt->close();
    } else {
        echo "Error preparing query: " . $conn->error;
    }
    
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
