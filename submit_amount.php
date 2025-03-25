<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

$job_id = isset($_POST['job_id']) ? $_POST['job_id'] : '';
$amount = isset($_POST['amount']) ? $_POST['amount'] : '';

if (!empty($job_id) && !empty($amount)) {
    
    $check_sql = "SELECT post_id FROM jobs_pendings WHERE post_id = ?";
    if ($check_stmt = $conn->prepare($check_sql)) {
        $check_stmt->bind_param("i", $job_id); 
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            
            $sql = "UPDATE jobs_pendings SET total_amount = ? WHERE post_id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ii", $amount, $job_id); 
                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Amount updated successfully']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update amount']);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to prepare statement']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Job not found']);
        }
        $check_stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to prepare check statement']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input']);
}

$conn->close();
?>
