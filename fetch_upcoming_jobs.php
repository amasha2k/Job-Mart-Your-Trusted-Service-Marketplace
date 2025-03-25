<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}


$username = isset($_GET['username']) ? $_GET['username'] : '';

if (!empty($username)) {
    
    $sql = "SELECT id, post_id, job_title, job_category, poster_username, job_location, job_image, job_date, job_end_date, C_mobile_number, email, address, total_amount 
            FROM jobs_pendings 
            WHERE username = ? AND status = 'accepted'";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $jobs = [];
        while ($row = $result->fetch_assoc()) {
            $jobs[] = $row; 
        }

        echo json_encode($jobs); 
        $stmt->close(); 
    } else {
        echo json_encode([]);
    }
} else {
    echo json_encode([]);
}

$conn->close(); 
?>
