<?php include_once 'Main_header.php'; ?>

 
    <div class="container">
        <form action="submit_job.php" method="post">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" required></textarea>

            <label for="location">Location</label>
            <select id="location" name="location" required>
            <option value="colombo">Colombo</option>
                <option value="gampaha">Gampaha</option>
                <option value="kalutara">Kalutara</option>
                <option value="kandy">Kandy</option>
                <option value="matale">Matale</option>
                <option value="nuwaraeliya">Nuwara Eliya</option>
                <option value="galle">Galle</option>
                <option value="matara">Matara</option>
                <option value="hambantota">Hambantota</option>
                <option value="jaffna">Jaffna</option>
                <option value="kilinochchi">Kilinochchi</option>
                <option value="mannar">Mannar</option>
                <option value="vavuniya">Vavuniya</option>
                <option value="mullaitivu">Mullaitivu</option>
                <option value="batticaloa">Batticaloa</option>
                <option value="ampara">Ampara</option>
                <option value="trincomalee">Trincomalee</option>
                <option value="kurunegala">Kurunegala</option>
                <option value="puttalam">Puttalam</option>
                <option value="anuradhapura">Anuradhapura</option>
                <option value="polonnaruwa">Polonnaruwa</option>
                <option value="badulla">Badulla</option>
                <option value="monaragala">Monaragala</option>
                <option value="ratnapura">Ratnapura</option>
                <option value="kegalle">Kegalle</option>
            
                
            </select>

            <label for="category">Select Category</label>
            <select id="category" name="category" required>
                < <option value="ac_repairs">AC Repairs</option>
                <option value="cctv">CCTV</option>
                <option value="construction">Construction</option>
                <option value="electronic_repairs">Electronic Repairs</option>
                <option value="glass">Glass</option>
                <option value="aluminium">Aluminium</option>
                <option value="iron_works">Iron Works</option>
                <option value="plumbing">Plumbing</option>
                <option value="wood_work">Wood Work</option>
                
            </select>

            <label for="image">Choose Image</label>
            <input type="file" id="image" name="image">

            <label for="jobType"></label>
            <input type="radio" id="scheduledJob" name="jobType" value="Scheduled Job" required>Scheduled Job
            <input type="radio" id="urgentJob" name="jobType" value="Urgent Job">Urgent Job

            <div id="dateContainer" style="display: none;">
                <label for="jobDate">Job Date</label>
                <input type="date" id="jobDate" name="jobDate">
            </div>

            <button type="submit" name="submit">Next</button>
            <button type="reset">Cancel</button>
        </form>
    </div>

    <script>
        document.getElementById('scheduledJob').addEventListener('change', function() {
            document.getElementById('dateContainer').style.display = 'block';
        });

        document.getElementById('urgentJob').addEventListener('change', function() {
            document.getElementById('dateContainer').style.display = 'none';
        });
    </script>


    <?php include_once 'Main_footer.php'; ?>