<?php 
include_once 'main_header.php'; 

$host = "localhost";
$user = "root";
$password = "";
$db = "job_mart";


$conn = mysqli_connect($host, $user, $password, $db);

if ($conn === false) {
    die("Connection failed: " . mysqli_connect_error());
}

 
$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';

if ($username === 'Guest') {
    header("Location: login.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-3 account-sidebar">
            <h4>Account</h4>
            <p><?php echo htmlspecialchars($username); ?></p>
            <a href="#" id="myAdsBtn">My ads</a>
            <a href="#" id="myProfileBtn">My Profile</a>
            <a href="#" id="myJobsRequestedBtn">My Jobs Requested</a>
            <a href="#" id="filteredJobsBtn">Ongoing Jobs</a>
            <a href="#" id="makePaymentBtn">Make Payment</a>
            <a href="#">Favorites</a>
            <a href="#">Settings</a>
            <h4 class="mt-4">Jobs</h4>
            <a href="#">Profile Database</a>
        </div>

        <div class="col-md-9 main-content" id="mainContainer" style="max-width: 70%;">
            <div class="no-ads-container">
                <h3>Welcome! Please select an option from the sidebar.</h3>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="paymentModal1" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="paymentModalLabel">Payment Information</h5>
      </div>
      <div class="modal-body">
        <form id="paymentForm">
          <div class="mb-3">
            <label for="nameOnCard" class="form-label">Name on Card</label>
            <input type="text" class="form-control" id="nameOnCard1" required>
          </div>
          <div class="mb-3">
            <label for="cardNumber" class="form-label">Card Number</label>
            <input type="text" class="form-control" id="cardNumber1" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <label for="expiryDate" class="form-label">MM/YY</label>
              <input type="text" class="form-control" id="expiryDate1" placeholder="MM/YY" required>
            </div>
            <div class="col-md-6">
              <label for="cvv" class="form-label">CVV</label>
              <input type="text" class="form-control" id="cvv1" required>
            </div>
          </div>
          <input type="hidden" id="paymentJobId">
          <input type="hidden" id="paymentAmount">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="confirmPaymentBtn">Pay</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Rate & Review</h5>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="star-rating">
                        <span class="star" data-value="5">&#9733;</span>
                        <span class="star" data-value="4">&#9733;</span>
                        <span class="star" data-value="3">&#9733;</span>
                        <span class="star" data-value="2">&#9733;</span>
                        <span class="star" data-value="1">&#9733;</span>
                    </div>
                    <div class="mb-3">
                        <label for="feedback" class="form-label">Your Feedback</label>
                        <textarea class="form-control" id="feedback" rows="3" required></textarea>
                    </div>
                    <input type="hidden" id="reviewJobId"> <!-- Ensure this is set when modal opens -->
                    <input type="hidden" id="ratingValue" value="5"> 
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="submitReviewBtn">Submit Review</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>





<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>



<script>
   $(document).ready(function(){
    $('#myAdsBtn').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'fetch_ads.php',
            type: 'GET',
            success: function(data) {
                const ads = JSON.parse(data);
                $('#mainContainer').html('');

                if (ads.length > 0) {
                    ads.forEach(function(ad) {
                        $('#mainContainer').append(`
                            <div class="ad-item mb-4 border p-3" style="max-width: 90%; margin: 0; text-align: left; display: flex; align-items: center;">
                                <div style="margin-right: 15px;">
                                    ${ad.image ? `<img src="uploads/${ad.image}" class="img-fluid" alt="Ad Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 120px; width: 120px; object-fit: cover;">'}
                                </div>
                                <div>
                                    <h5 class="mb-1">${ad.title}</h5>
                                    <p class="mb-1"><strong>Job Type:</strong> ${ad.jobType}</p>
                                    <p class="mb-1"><strong>Category:</strong> ${ad.category}</p>
                                    <div class="text-left mt-3">
                                        <button class="btn btn-danger delete-btn" data-id="${ad.id}">Delete</button>
                                    </div>
                                </div>
                            </div>
                        `);
                    });

                    
                    $('.delete-btn').click(function() {
                        const adId = $(this).data('id');
                        if (confirm('Are you sure you want to delete this ad?')) {
                            deleteAd(adId);
                        }
                    });
                } else {
                    $('#mainContainer').append('<h3>No approved ads found.</h3>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching ads:', textStatus, errorThrown);
                $('#mainContainer').html('<h3>Error fetching ads. Please try again later.</h3>');
            }
        });
    });

    function deleteAd(adId) {
    $.ajax({
        url: 'delete_ad.php', 
        type: 'POST',
        data: { id: adId },
        dataType: 'json', 
        success: function(response) {
            if (response.success) {
                alert(response.message); 
                $('#myAdsBtn').click(); 
            } else {
                alert('Error deleting ad: ' + response.message); 
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error deleting ad:', textStatus, errorThrown);
            alert('An unexpected error occurred. Please try again later.'); 
        }
    });


    }



        $('#myProfileBtn').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'fetch_profile.php',
                type: 'GET',
                success: function(data) {
                    const profile = JSON.parse(data);
                    $('#mainContainer').html('');

                    if (profile) {
                        $('#mainContainer').append(`
                            <div class="profile-container">
                                <h3>My Profile</h3>
                                <div class="profile-info"><strong>First Name:</strong> ${profile.first_name}</div>
                                <div class="profile-info"><strong>Last Name:</strong> ${profile.last_name}</div>
                                <div class="profile-info"><strong>Username:</strong> ${profile.username}</div>
                                <div class="profile-info"><strong>Email:</strong> ${profile.email}</div>
                                <div class="profile-info"><strong>Mobile Number:</strong> ${profile.mobile_number}</div>
                                <div class="profile-info"><strong>Address:</strong> ${profile.address}</div>
                                <div class="profile-info"><strong>District:</strong> ${profile.district}</div>
                                <div class="profile-info"><strong>Account Created At:</strong> ${profile.created_at}</div>
                            </div>
                        `);
                    } else {
                        $('#mainContainer').append('<h3>No profile information found.</h3>');
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error('Error fetching profile:', textStatus, errorThrown);
                    $('#mainContainer').html('<h3>Error fetching profile. Please try again later.</h3>');
                }
            });
        });

        $(document).on('click', '#viewMoreBtn', function() {
            alert('View More functionality to be implemented!');
        });
    });

    $(document).ready(function() {
    

    
    $('#myJobsRequestedBtn').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'fetch_jobs_pending.php', 
            type: 'GET',
            success: function(data) {
                const jobs = JSON.parse(data);
                $('#mainContainer').html(''); 

                if (jobs.length > 0) {
                    jobs.forEach(function(job) {
                        $('#mainContainer').append(`
                            <div class="job-item mb-4 border p-3 d-flex" style="max-width: 90%; margin: 0; text-align: left; display: flex; align-items: center;">
                                <div class="image-container" style="flex: 1; max-width: 150px;">
                                  ${job.job_image ? `<img src="uploads/${job.job_image}" class="img-fluid" alt="Ad Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 120px; width: 120px; object-fit: cover;">'}
                                </div>
                                <div class="job-details" style="flex: 2; padding-left: 20px;">
                                  <h2 class="mb-1"><strong>Job Title:</strong> ${job.job_title}</h2>
                                  <p class="mb-1"><strong>Job Category:</strong> ${job.job_category}</p>
                                  <p class="mb-1"><strong>Job Location:</strong> ${job.job_location}</p>
                                  <p class="mb-1"><strong>Employe Name:</strong> ${job.username}</p>
                                  <p class="mb-1"><strong>EMP Mobile:</strong> ${job.mobile_number}</p>
                                  <p class="mb-1"><strong>EMP Email:</strong> ${job.emp_email}</p>
                                  <p class="mb-1"><strong>EMP Review Rating:</strong> ${job.review_rating}</p>
                                  <p class="mb-1"><strong>Status:</strong> ${job.status}</p>
                                  <p class="mb-1"><strong>Created At:</strong> ${job.created_at}</p>
                                  <p class="mb-1"><strong>Job Date:</strong> ${job.job_date}</p>
                                  <button class="btn btn-success accept-job" data-id="${job.id}">Accept</button>
                                  <button class="btn btn-danger reject-job" data-id="${job.id}">Reject</button>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    $('#mainContainer').append('<h3>No jobs found.</h3>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching jobs:', textStatus, errorThrown);
                $('#mainContainer').html('<h3>Error fetching jobs. Please try again later.</h3>');
            }
        });
    });

    
   
   $('#mainContainer').on('click', '.accept-job', function() {
    const jobId = $(this).data('id');
    
    $.ajax({
        url: 'accept_job.php', 
        type: 'POST',
        data: { id: jobId },
        success: function(response) {
            
            alert(response); 
            $('#myJobsRequestedBtn').click(); 
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error('Error accepting job:', textStatus, errorThrown);
            alert('Error accepting job. Please try again later.');
        }
    });
});



    $('#mainContainer').on('click', '.reject-job', function() {
        const jobId = $(this).data('id');
        
        $.ajax({
            url: 'reject_job.php', 
            type: 'POST',
            data: { id: jobId },
            success: function(response) {
                
                alert('Job rejected successfully!');
                $('#myJobsRequestedBtn').click(); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error rejecting job:', textStatus, errorThrown);
                alert('Error rejecting job. Please try again later.');
            }
        });
    });

    $(document).ready(function() {
    $('#filteredJobsBtn').click(function(e) {
        e.preventDefault();

        $.ajax({
            url: 'fetch_filtered_jobs.php',
            type: 'GET',
            success: function(data) {
                const jobs = JSON.parse(data);
                $('#mainContainer').html(''); 

                if (jobs.length > 0) {
                    jobs.forEach(job => {
                        $('#mainContainer').append(`
                            <div class="job-container row mb-4">
                                <div class="col-md-3">
                                    ${job.job_image ? `<img src="uploads/${job.job_image}" class="img-fluid" alt="Job Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 150px; width: 150px; object-fit: cover;">'}
                                </div>
                                <div class="col-md-9">
                                    <h4>${job.job_title}</h4>
                                    <p><strong>Category:</strong> ${job.job_category}</p>
                                    <p><strong>Location:</strong> ${job.job_location}</p>
                                    <p><strong>Job Date:</strong> ${job.job_date} to ${job.job_end_date}</p>
                                    <p><strong>Total Amount:</strong> Rs: ${job.total_amount}</p>
                                    <p><strong>Job Start:</strong> ${job.Start_job}</p>
                                    
                                    <!-- Add "Report this employee" button -->
                                    <button class="btn btn-danger report-employee-btn" data-id="${job.id}" data-employee="${job.username}">Report this employee</button>
                                </div>
                            </div>
                            <hr/>
                        `);
                    });

                    
                    $('.report-employee-btn').click(function() {
                        const employeeId = $(this).data('id');
                        const employeeName = $(this).data('employee');
                        
                        
                        const username = '<?php echo $_SESSION['username']; ?>';
                        const mobileNumber = '<?php echo $_SESSION['mobile_number']; ?>';
                        const email = '<?php echo $_SESSION['email']; ?>';
                        
                        
                        const reason = prompt(`Why are you reporting employee: ${employeeName}?`);
                        if (reason) {
                            
                            $.ajax({
                                url: 'report_employee.php',
                                type: 'POST',
                                data: {
                                    id: employeeId,
                                    employee: employeeName,
                                    reason: reason,
                                    username: username,             
                                    mobile_number: mobileNumber,     
                                    email: email                     
                                },
                                success: function(response) {
                                    alert(response);  
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    alert('Error reporting employee. Please try again later.');
                                    console.error('Error:', textStatus, errorThrown);
                                }
                            });
                        } else {
                            alert("Report canceled. Please provide a reason for reporting the employee.");
                        }
                    });

                }
            }
        });
    });
});


$(document).ready(function() {
   

    
    $('#makePaymentBtn').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'fetch_jobs_payment.php',
            type: 'GET',
            success: function(data) {
                const jobs = JSON.parse(data);
                $('#mainContainer').html('');

                if (jobs.length > 0) {
                    jobs.forEach(job => {
                        $('#mainContainer').append(`
                            <div class="job-container row mb-4">
                                <div class="col-md-3">
                                    ${job.job_image ? `<img src="uploads/${job.job_image}" class="img-fluid" alt="Job Image" style="height: 150px; width: 150px; object-fit: cover;">` : '<img src="placeholder.jpg" class="img-fluid" alt="Placeholder Image" style="height: 150px; width: 150px; object-fit: cover;">'}
                                </div>
                                <div class="col-md-9">
                                    <h4>${job.job_title}</h4>
                                    <p><strong>Category:</strong> ${job.job_category}</p>
                                    <p><strong>Location:</strong> ${job.job_location}</p>
                                    <p><strong>Total Amount:</strong> Rs: ${job.total_amount}</p>
                                    <p><strong>Job Done Date:</strong> ${job.job_done}</p>
                                    <button class="btn btn-success make-payment-btn" data-id="${job.id}" data-amount="${job.total_amount}">Make Payment</button>
                                    <span class="payment-success-message" style="display:none; color:green;">Payment Successful</span>
                                </div>
                            </div>
                            <hr/>
                        `);
                    });

                    $('.make-payment-btn').click(function() {
                        const jobId = $(this).data('id');
                        const amount = $(this).data('amount');
                        const paymentButton = $(this);
                        const paymentSuccessMessage = paymentButton.siblings('.payment-success-message');

                        $.ajax({
                            url: 'fetch_payment_details.php', 
                            type: 'GET',
                            success: function(userDetails) {
                                const user = JSON.parse(userDetails);

                                if (user.error) {
                                    alert(user.error);
                                } else {
                                    $('#nameOnCard1').val(user.nameOnCard);
                                    $('#cardNumber1').val(user.cardNumber);
                                    $('#expiryDate1').val(user.expiryDate);
                                    $('#cvv1').val(user.cvv);
                                    $('#paymentJobId').val(jobId);
                                    $('#paymentAmount').val(amount);
                                    $('#paymentModal1').modal('show');
                                }
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                console.error('Error fetching user payment details:', textStatus, errorThrown);
                                alert('Error fetching payment details. Please try again later.');
                            }
                        });

                        $('#confirmPaymentBtn').off('click').on('click', function() { // Use off() to prevent multiple bindings
                            const nameOnCard = $('#nameOnCard1').val();
                            const cardNumber = $('#cardNumber1').val();
                            const expiryDate = $('#expiryDate1').val();
                            const cvv = $('#cvv1').val();
                            const jobId = $('#paymentJobId').val();
                            const amount = $('#paymentAmount').val();
                            const username = '<?php echo $_SESSION["username"]; ?>';

                            if (!nameOnCard || !cardNumber || !expiryDate || !cvv) {
                                alert('Please fill in all payment details.');
                                return; 
                            }

                            $.ajax({
                                url: 'process_payment.php',
                                type: 'POST',
                                data: {
                                    job_id: jobId,
                                    amount: amount,
                                    card_number: cardNumber,
                                    expiry_date: expiryDate,
                                    cvv: cvv,
                                    name_on_card: nameOnCard,
                                    username: username 
                                },
                                success: function(response) {
                                    alert(response); 
                                    $('#paymentModal1').modal('hide'); 

                                    paymentButton.hide();
                                    paymentSuccessMessage.show();

                                    
                                    $('#reviewJobId').val(jobId);  
                                    $('#reviewModal').modal('show');  
                                },
                                error: function(jqXHR, textStatus, errorThrown) {
                                    alert('Error processing payment. Please try again later.');
                                    console.error('Error:', textStatus, errorThrown);
                                }
                            });
                        });
                    });
                } else {
                    $('#mainContainer').append('<h3>No jobs found for payment.</h3>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('Error fetching jobs for payment:', textStatus, errorThrown);
                $('#mainContainer').html('<h3>Error fetching jobs. Please try again later.</h3>');
            }
        });
    });

    

   
    $(document).on('click', '.open-review-modal', function() {
        const postId = $(this).data('post-id'); 
        $('#reviewJobId').val(postId); 
        $('#ratingValue').val(5); 
        $('.star-rating .star').removeClass('selected'); 
        $('#reviewModal').modal('show'); 
    });

    
    $('.star-rating .star').click(function() {
        const ratingValue = $(this).data('value');
        $('#ratingValue').val(ratingValue);
        $('.star-rating .star').removeClass('selected');
        $('.star-rating .star').each(function() {
            if ($(this).data('value') <= ratingValue) {
                $(this).addClass('selected');
            }
        });
    });

    
    $('#submitReviewBtn').click(function() {
        const jobId = $('#reviewJobId').val();
        const rating = $('#ratingValue').val();
        const feedback = $('#feedback').val();

        if (!feedback || !rating) {
            alert('Please provide both a rating and feedback.');
            return;
        }

        $.ajax({
            url: 'submit_review.php', 
            type: 'POST',
            data: {
                job_id: jobId,
                rating: rating,
                feedback: feedback
            },
            success: function(response) {
                alert('Review submitted successfully!');
                $('#reviewModal').modal('hide'); 
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error submitting review. Please try again later.');
                console.error('Error:', textStatus, errorThrown);
            }
        });
    });
});



});


</script>

<?php include_once 'main_footer.php'; ?>
