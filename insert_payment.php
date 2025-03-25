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




$nameOnCard1 = $_POST['name_on_card'];
$cardNumber1 = $_POST['card_number'];
$expiryDate1 = $_POST['expiry_date'];
$cvv1 = $_POST['cvv'];
$jobId = $_POST['job_id'];
$amount = $_POST['amount'];
$username = $_SESSION['username']; 


$jobQuery = "SELECT * FROM jobs_pendings WHERE post_id = ?";
$stmt = $conn->prepare($jobQuery);
$stmt->bind_param("i", $jobId);
$stmt->execute();
$jobResult = $stmt->get_result();
$jobData = $jobResult->fetch_assoc();


$posterUsername = $jobData['poster_username'];
$jobCategory = $jobData['job_category'];
$jobLocation = $jobData['job_location'];
$jobImage = $jobData['job_image'];
$email = $jobData['email'];
$empEmail = $jobData['emp_email'];


$paymentQuery = "INSERT INTO payments (post_id, username, job_category, poster_username, job_location, job_image, email, total_amount, emp_email, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
$stmt = $conn->prepare($paymentQuery);
$stmt->bind_param("issssssis", $jobId, $username, $jobCategory, $posterUsername, $jobLocation, $jobImage, $email, $amount, $empEmail);

if ($stmt->execute()) {
    
    $updateJobQuery = "UPDATE jobs_pendings SET status = 'Paid' WHERE post_id = ?";
    $stmt = $conn->prepare($updateJobQuery);
    $stmt->bind_param("i", $jobId);
    $stmt->execute();

    echo "Payment successfully processed!";
} else {
    echo "Error processing payment: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
