<?php include_once 'Admin_header.php'; ?>
<?php 
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";

$data = mysqli_connect($host, $user, $password, $db);

if ($data === false) {
    die("Connection failed: " . mysqli_connect_error());
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['approve'])) {
    $post_id = mysqli_real_escape_string($data, $_POST['post_id']);

    $sql = "UPDATE job_posting SET status='approved' WHERE id='$post_id'";

    if (mysqli_query($data, $sql)) {
        echo "<script>alert('Post approved successfully!'); window.location.href='#';</script>";
    } else {
        echo "Error: " . mysqli_error($data);
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reject'])) {
    $post_id = mysqli_real_escape_string($data, $_POST['post_id']);

    $sql = "UPDATE job_posting SET status='rejected' WHERE id='$post_id'";

    if (mysqli_query($data, $sql)) {
        echo "<script>alert('Post rejected successfully!'); window.location.href='#';</script>";
    } else {
        echo "Error: " . mysqli_error($data);
    }
}
?>

<div class="container">
    <h1>Admin Post Approval</h1>
    
    <?php
    
    $sql = "SELECT * FROM job_posting WHERE status = 'pending'";
    $result = mysqli_query($data, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $imagePath = 'photos_img/' . $row['image']; 
            ?>
            <div class="post-container">
                <img src="<?php echo $imagePath; ?>" alt="Job Image" class="post-image">
                <div class="post-details">
                    <h2><?php echo $row['title']; ?></h2>
                    <p><?php echo $row['description']; ?></p>
                    <p><strong>Location:</strong> <?php echo $row['location']; ?></p>
                    <p><strong>Category:</strong> <?php echo $row['category']; ?></p>
                    <p><strong>Job Date:</strong> <?php echo $row['jobDate']; ?></p>
                    <p><strong>Job End Date:</strong> <?php echo $row['jobEndDate']; ?></p>
                </div>
                <div class="post-actions">
                    <form method="post" action="">
                        <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="approve" class="btn btn-approve">Approve</button>
                    </form>
                    <form method="post" action="">
                        <input type="hidden" name="post_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="reject" class="btn btn-reject">Reject</button>
                    </form>
                </div>
            </div>
            <?php
        }
    } else {
        echo "<p>No posts to approve.</p>";
    }
    ?>
</div>

<?php include_once 'Admin_footer.php'; ?>
