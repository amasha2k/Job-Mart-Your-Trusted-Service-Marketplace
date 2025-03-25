<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;



require __DIR__ . '/PHPMailer-master/src/Exception.php';
require __DIR__ . '/PHPMailer-master/src/PHPMailer.php';
require __DIR__ . '/PHPMailer-master/src/SMTP.php';

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die(json_encode(['message' => "Connection failed: " . mysqli_connect_error()]));
}


if (isset($_POST['job_id']) && isset($_POST['action'])) {
    $job_id = $_POST['job_id'];
    $action = $_POST['action'];
    $current_time = date('Y-m-d H:i:s');

    
    $sql = "SELECT job_title, email, total_amount FROM jobs_pendings WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $job_id);
    $stmt->execute();
    $stmt->bind_result($job_title, $email, $total_amount);
    $stmt->fetch();
    $stmt->close();

    
    $mail = new PHPMailer(true);

    try {
        
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'dulminethusha57@gmail.com';  
        $mail->Password   = 'munr upxg rfxr pzcd';  
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        
        $mail->setFrom('dulminethusha57@gmail.com', 'JobMart');  
        $mail->addAddress($email);  

        if ($action == 'start') {
            
            $sql = "UPDATE jobs_pendings SET Start_job = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $current_time, $job_id);
            if ($stmt->execute()) {
               
                $mail->isHTML(true);
                $mail->Subject = 'Your Job has Started';
                $mail->Body    = "Your job <b>$job_title</b> has been started by the employee.";
                $mail->send();

                echo json_encode(['message' => 'Job started successfully! An email has been sent to the job owner.']);
            } else {
                echo json_encode(['message' => 'Failed to start job.']);
            }
            $stmt->close();

        } elseif ($action == 'done') {
            
            $sql = "UPDATE jobs_pendings SET job_done = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $current_time, $job_id);
            if ($stmt->execute()) {
                
                $mail->isHTML(true);
                $mail->Subject = 'Your Job is Completed';
                $mail->Body    = "Your job <b>$job_title</b> is completed. The total amount is Rs: $total_amount<br>Please visit the website to make your payment.";
                $mail->send();

                echo json_encode(['message' => 'Job marked as done! An email has been sent to the job owner.']);
            } else {
                echo json_encode(['message' => 'Failed to mark job as done.']);
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        echo json_encode(['message' => "Job updated, but the email could not be sent. Error: {$mail->ErrorInfo}"]);
    }
} else {
    echo json_encode(['message' => 'Missing job_id or action parameter.']);
}

$conn->close();
?>
