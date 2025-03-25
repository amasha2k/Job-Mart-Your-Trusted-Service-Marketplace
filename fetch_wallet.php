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

if ($username === null) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit();
}


$query = "SELECT emp_amount, job_mart_commission FROM payments WHERE emp_username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

$total_income = 0;
$total_commission = 0;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Calculate the total amount (emp_amount + job_mart_commission)
        $total_income += $row['emp_amount'];
        $total_commission += $row['job_mart_commission'];
    }

    // Calculate the overall total (employee's income + job mart commission)
    $overall_total = $total_income + $total_commission;

    echo json_encode([
        'status' => 'success',
        'total_income' => $total_income,
        'total_commission' => $total_commission,
        'overall_total' => $overall_total
    ]);
} else {
    echo json_encode([
        'status' => 'success',
        'total_income' => 0,
        'total_commission' => 0,
        'overall_total' => 0,
        'message' => 'No payments found.'
    ]);
}

$stmt->close();
$conn->close();
?>
