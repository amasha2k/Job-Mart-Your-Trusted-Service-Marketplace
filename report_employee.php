<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $employeeId = $_POST['id'];
    $employeeName = $_POST['employee'];
    $reportReason = $_POST['reason'];
    $username = $_POST['username'];    
    $mobileNumber = $_POST['mobile_number'];  
    $email = $_POST['email'];          

    
    $host = "localhost";
    $user = "root";
    $password = "";
    $db = "job_mart";

    $conn = mysqli_connect($host, $user, $password, $db);
    
    
    if ($conn === false) {
        die("Error: " . mysqli_connect_error());
    }

    
    $sql = "INSERT INTO employee_reports (employee_id, employee_name, report_time, report_reason, reported_by_username, reported_by_mobile, reported_by_email) 
            VALUES (?, ?, NOW(), ?, ?, ?, ?)";
    
   
    $stmt = $conn->prepare($sql);
    
    
    $stmt->bind_param("isssss", $employeeId, $employeeName, $reportReason, $username, $mobileNumber, $email);
    
    
    $stmt->execute();

    
    if ($stmt->affected_rows > 0) {
        echo "Employee reported successfully! we will contact you within 24Hrs.";
    } else {
        echo "Failed to report employee.";
    }

    
    $stmt->close();
    $conn->close();
}
?>

