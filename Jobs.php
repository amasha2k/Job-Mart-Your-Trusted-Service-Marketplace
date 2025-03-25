<?php include_once 'main_header.php'; ?>
<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}


$query = "SELECT * FROM job_posting WHERE status = 'approved' AND jobType = 'Scheduled Job'";


if (isset($_GET['category']) && !empty($_GET['category'])) {
    $selected_category = mysqli_real_escape_string($conn, $_GET['category']);
    
    $query .= " AND category = '$selected_category'";
}


$query .= " ORDER BY category";


$result = mysqli_query($conn, $query);
?>

<div class="container-jobs">
    <div class="sidebar">
        <h3>Categories</h3>
        <ul>
            <li><a href="Jobs.php">All</a></li> 
            <li><a href="?category=ac_repairs">AC Repairs</a></li>
            <li><a href="?category=cctv">CCTV</a></li>
            <li><a href="?category=Construction">Construction</a></li>
            <li><a href="?category=Electronic_Repairs">Electronic Repairs</a></li>
            <li><a href="?category=Glass">Glass</a></li>
            <li><a href="?category=Aluminium">Aluminium</a></li>
            <li><a href="?category=Housekeeping">Housekeeping</a></li>
            <li><a href="?category=Iron%20Works">Iron Works</a></li>
            <li><a href="?category=Plumbing">Plumbing</a></li>
            <li><a href="?category=Woodwork">Woodwork</a></li>
            <li><a href="?category=Other">Other</a></li>
            <li><a href="?category=ac_repairs">AC Repairs</a></li>
            <li><a href="?category=cctv">CCTV</a></li>
            <li><a href="?category=Construction">Construction</a></li>
            <li><a href="?category=Electronic%20Repairs">Electronic Repairs</a></li>
            <li><a href="?category=glass">Glass</a></li>
            <li><a href="?category=Aluminium">Aluminium</a></li>
            <li><a href="?category=Iron%20Works">Iron Works</a></li>
            <li><a href="?category=Plumbing">Plumbing</a></li>
            <li><a href="?category=wood_work">Woodwork</a></li>
            <li><a href="?category=Other">Other</a></li>
        </ul>
    </div>
    <div class="job-listings">
    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while($row = mysqli_fetch_assoc($result)): ?>
            <div class="job-post">
                <div class="job-image">
                    <img src="photos_img/<?php echo htmlspecialchars($row['image']); ?>" alt="Job Image">
                </div>
                <div class="job-details">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p><strong>Job Type:</strong> <?php echo htmlspecialchars($row['jobType']); ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($row['location']); ?></p>
                    <p><strong>Job Date:</strong> <?php echo htmlspecialchars($row['jobDate']); ?></p>
                    <p><strong>End Date:</strong> <?php echo htmlspecialchars($row['jobEndDate']); ?></p>
                    
                    
                    <a href="view_more.php?job_id=<?php echo htmlspecialchars($row['id']); ?>&username=<?php echo urlencode($row['username']); ?>&title=<?php echo urlencode($row['title']); ?>&location=<?php echo urlencode($row['location']); ?>&image=<?php echo urlencode($row['image']); ?>&jobDate=<?php echo urlencode($row['jobDate']); ?>&jobEndDate=<?php echo urlencode($row['jobEndDate']); ?>&C_mobile_number=<?php echo urlencode($row['mobile_number']); ?>&email=<?php echo urlencode($row['email']); ?>&address=<?php echo urlencode($row['address']); ?>">
                        <button class="view-more">View More</button>
                    </a>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No scheduled jobs available.</p>
    <?php endif; ?>
    </div>
</div>

<?php include_once 'Main_footer.php'; ?>
