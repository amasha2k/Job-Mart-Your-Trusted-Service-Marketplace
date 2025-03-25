<?php include_once 'Main_header.php'; ?> 

<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}


if (isset($_GET['job_id']) && !empty($_GET['job_id'])) {
    $job_id = mysqli_real_escape_string($conn, $_GET['job_id']);
    $query = "SELECT * FROM job_posting WHERE id = '$job_id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        
        $C_mobile_number = $_GET['C_mobile_number'] ?? ''; 
        $email = $_GET['email'] ?? '';
        $address = $_GET['address'] ?? '';

        
        $mobile_number = $_SESSION['mobile_number'] ?? '';  
        $emp_email = $_SESSION['email'] ?? '';  

        
        $username = $_SESSION['username'] ?? '';  
        $usertype = $_SESSION['user_type'] ?? '';
        $review_rating = $_SESSION['review_rating'] ?? 0;
        $user_job_category = $_SESSION['job_category'] ?? '';
        $poster_username = $row['username'];
        $title = $row['title'];
        $postid = $row['id'];
        $location = $row['location'];
        $image = $row['image'];
        $jobDate = $row['jobDate'];
        $jobEndDate = $row['jobEndDate'];

        $message = ''; 

        if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['apply'])) {
            
            $check_application_query = "SELECT * FROM jobs_pendings WHERE username = '$username' AND job_title = '$title'";
            $check_result = mysqli_query($conn, $check_application_query);

            if (mysqli_num_rows($check_result) > 0) {
                $message = "<div style='color: red;'>You have already applied for this job.</div>";
            } else {
                
                $job_category = $row['category']; 

                if ($user_job_category == $job_category) {
                    
                    $insert_query = "INSERT INTO jobs_pendings (post_id, username, user_type, job_category, mobile_number, review_rating, 
                            poster_username, job_title, job_location, job_image, job_date, job_end_date, C_mobile_number, email, emp_email, address) 
                            VALUES ('$postid', '$username', '$usertype', '$job_category', '$mobile_number', '$review_rating', 
                                    '$poster_username', '$title', '$location', '$image', '$jobDate', '$jobEndDate', '$C_mobile_number', '$email', '$emp_email', '$address')";
                    if (mysqli_query($conn, $insert_query)) {
                        $message = "<div style='color: green;'>Application submitted successfully.</div>";
                    } else {
                        $message = "<div style='color: red;'>Error: " . mysqli_error($conn) . "</div>";
                    }
                } else {
                    $message = "<div style='color: red;'>You are not registered in this job category.</div>";
                }
            }
        }
?>

<div class="job-container">
    
    <div class="profile-section">
        <div class="profile-image">
            
        </div>
        <div class="profile-info">
            <h3><?php echo htmlspecialchars($row['username']); ?></h3>
            <p>For 0 jobs</p>
            <div class="contact-btns">
                <button><i class="fas fa-phone"></i></button>
                <button><i class="fas fa-envelope"></i></button>
            </div>
        </div>
    </div>
    
    
    <div class="details-section">
       
        <?php if (!empty($message)): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        <h2><?php echo htmlspecialchars($row['title']); ?></h2>
        <div class="detail-item">
            <label>Category:</label> <?php echo htmlspecialchars($row['category']); ?>
        </div>
        <div class="detail-item">
            <label>Location:</label> <?php echo htmlspecialchars($row['location']); ?>
        </div>
        <div class="detail-item">
            <label>Due Date:</label> <?php echo htmlspecialchars($row['jobDate']); ?>
        </div>
        <div class="detail-item">
            <label>Posted:</label> <?php echo htmlspecialchars($row['jobEndDate']); ?>
        </div>
        <p class="description">
            <?php echo htmlspecialchars($row['description']); ?>
        </p>

        
        <div class="images">
            <?php if (!empty($row['image'])): ?>
                <img src="photos_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Job Image">
            <?php else: ?>
                <p>No image provided.</p>
            <?php endif; ?>
        </div>

        
        <div class="apply-section">
            <form method="POST" action="">
                
                <input type="hidden" name="C_mobile_number" value="<?php echo htmlspecialchars($C_mobile_number); ?>">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($email); ?>">
                <input type="hidden" name="address" value="<?php echo htmlspecialchars($address); ?>">
                <button class="apply-btn" name="apply">Apply This Job</button>
            </form>
        </div>
    </div>

    
    <div class="share-icons">
        <i class="fab fa-facebook-f"></i>
        <i class="fab fa-twitter"></i>
        <i class="fab fa-linkedin-in"></i>
    </div>
</div>

<?php
    } else {
        echo "Job not found.";
    }
} else {
    echo "No job ID provided.";
}
?>

<?php include_once 'Main_footer.php'; ?>
