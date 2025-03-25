<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = new mysqli($host, $user, $password, $db);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    file_put_contents('log.txt', print_r($_POST, true));
    
    
    $post_id = isset($_POST['job_id']) ? intval($_POST['job_id']) : 0; 
    $rating = isset($_POST['rating']) ? $_POST['rating'] : null;
    $feedback = isset($_POST['feedback']) ? $_POST['feedback'] : '';

    
    if ($post_id === 0 || is_null($rating) || empty($feedback)) {
        echo json_encode(["status" => "error", "message" => "All fields are required."]);
        exit(); 
    }

    
    $stmt = $conn->prepare("UPDATE jobs_pendings SET review_rating = ?, feedback = ? WHERE id = ?");
    
    
    if ($stmt === false) {
        echo json_encode(["status" => "error", "message" => "Error preparing statement: " . $conn->error]);
        exit();
    }
    
    $stmt->bind_param("ssi", $rating, $feedback, $post_id);

    
    if ($stmt->execute()) {
        
        if ($stmt->affected_rows > 0) {
            echo json_encode(["status" => "success", "message" => "Review submitted successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "No rows updated. Please check if the post ID is correct."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Error executing query: " . $stmt->error]);
    }

   
    $stmt->close();
}
$conn->close();
?>
