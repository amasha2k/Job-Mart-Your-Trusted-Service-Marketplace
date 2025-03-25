<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $jobId = $_POST['job_id'];
    $amount = $_POST['amount'];
    $cardNumber = $_POST['card_number'];
    $expiryDate = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];
    $nameOnCard = $_POST['name_on_card'];
    $username = $_POST['username']; 

    
    $jobQuery = "SELECT * FROM jobs_pendings WHERE id = ?";
    $stmt = $conn->prepare($jobQuery);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();
    $jobResult = $stmt->get_result();

    if ($jobResult->num_rows > 0) {
        $job = $jobResult->fetch_assoc();

        
        $commission = $amount * 0.05;  // 5% of total amount
        $empAmount = $amount * 0.95;   // 95% of total amount

        
        $insertPayment = "INSERT INTO payments (post_id, username, job_category, poster_username, job_location, job_image, email, total_amount, emp_email, emp_username, job_mart_commission, emp_amount, created_at) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($insertPayment);
        $stmt->bind_param("issssssissdd", $job['post_id'], $username, $job['job_category'], $job['poster_username'], $job['job_location'], $job['job_image'], $job['email'], $amount, $job['emp_email'], $job['username'], $commission, $empAmount); 

        if ($stmt->execute()) {
            
            $insertProgress = "INSERT INTO payment_progress (post_id, username, job_category, poster_username, job_location, job_image, email, total_amount, emp_email, emp_username, job_mart_commission, emp_amount, created_at) 
                               VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insertProgress);
            $stmt->bind_param("issssssissdd", $job['post_id'], $username, $job['job_category'], $job['poster_username'], $job['job_location'], $job['job_image'], $job['email'], $amount, $job['emp_email'], $job['username'], $commission, $empAmount);

            if ($stmt->execute()) {
                echo "Payment processed successfully and recorded in progress!";
            } else {
                echo "Error recording payment progress: " . $stmt->error;
            }
        } else {
            echo "Error processing payment: " . $stmt->error;
        }
    } else {
        echo "Job not found.";
    }
    
    $stmt->close();
}
$conn->close();
?>
