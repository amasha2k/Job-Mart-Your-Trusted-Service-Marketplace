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

$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$withdrawAmount = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;

if ($username === null) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}

if ($withdrawAmount <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid withdrawal amount']);
    exit();
}


$query = "SELECT * FROM payments WHERE emp_username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if ($row) {
    $total_income = $row['emp_amount'];

    if ($total_income >= $withdrawAmount) {
        
        $update_query = "UPDATE payments SET emp_amount = emp_amount - ? WHERE emp_username = ? AND emp_amount >= ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param("dss", $withdrawAmount, $username, $withdrawAmount); // Ensure enough balance is available
        $update_success = $update_stmt->execute();

        if ($update_success) {
            
            $insert_query = "INSERT INTO payment_Analyze (post_id, username, job_category, poster_username, job_location, job_image, email, total_amount, emp_email, created_at, emp_username, job_mart_commission, emp_amount)
                             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $insert_stmt = $conn->prepare($insert_query);
            $insert_stmt->bind_param(
                "issssssssssis",
                $row['post_id'], 
                $row['username'],
                $row['job_category'],
                $row['poster_username'],
                $row['job_location'],
                $row['job_image'],
                $row['email'],
                $row['total_amount'],
                $row['emp_email'],
                $row['created_at'],
                $row['emp_username'],
                $row['job_mart_commission'],
                $withdrawAmount 
            );

            if ($insert_stmt->execute()) {
                echo json_encode([
                    'status' => 'success',
                    'withdrawn_amount' => $withdrawAmount
                ]);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert into payment_Analyze.']);
            }

            $insert_stmt->close();
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update the payment record.']);
        }

        $update_stmt->close();
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Insufficient funds for withdrawal.']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Payment record not found.']);
}

$stmt->close();
$conn->close();
?>
