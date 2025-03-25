<?php

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
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['id'])) {
    $jobId = $_POST['id'];

    
    $sql = "SELECT post_id,  emp_email FROM jobs_pendings WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $jobId);
        $stmt->execute();
        $stmt->bind_result($postId, $email);
        $stmt->fetch();
        $stmt->close();

        
        $sql = "UPDATE jobs_pendings SET status = 'Accepted' WHERE id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $jobId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
               
                $deleteSql = "DELETE FROM jobs_pendings WHERE post_id = ? AND id != ?";
                if ($deleteStmt = $conn->prepare($deleteSql)) {
                    $deleteStmt->bind_param("ii", $postId, $jobId);
                    $deleteStmt->execute();
                    $deleteStmt->close();
                }

                
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

                    
                    $mail->isHTML(true);                                        
                    $mail->Subject = 'Job Accepted Notification';
                    $mail->Body    = "Your job with post ID $postId has been accepted by the customer.";

                    $mail->send();
                    echo "Job accepted successfully. An email has been sent to the job owner. Other jobs with the same post_id have been deleted.";
                } catch (Exception $e) {
                    echo "Job accepted, but the email could not be sent. Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "Failed to accept the job. Please try again.";
            }

            $stmt->close();
        } else {
            echo "Error preparing query: " . $conn->error;
        }
    } else {
        echo "Error preparing query: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>
