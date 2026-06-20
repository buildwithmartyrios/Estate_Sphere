<?php 
include('db_config.php'); 
include('header.php'); 

// Security check: Must be logged in
if(!isset($_SESSION['user_id'])) { 
    echo "<script>window.location.href = 'login.php';</script>";
    exit(); 
}

$user_id = $_SESSION['user_id'];
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px; min-height: 50vh;">
    <h2 style="color: var(--navy); border-bottom: 2px solid var(--crimson); padding-bottom: 10px; display: inline-block;">My Reservations 📅</h2>
    <p style="color: #666; margin-bottom: 30px;">Track the status of your property inquiries and site visits.</p>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
        <?php
        // Fetch reservations combined with property details
        $query = "SELECT r.res_date, r.status, p.title, p.price, p.image_path, p.prop_id 
                  FROM reservations r 
                  JOIN properties p ON r.prop_id = p.prop_id 
                  WHERE r.user_id = '$user_id' 
                  ORDER BY r.res_date DESC";
                  
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            echo '<table style="width: 100%; border-collapse: collapse; text-align: left;">';
            echo '<tr style="background: var(--navy); color: white;">
                    <th style="padding: 15px; border-top-left-radius: 10px;">Property</th>
                    <th style="padding: 15px;">Date Reserved</th>
                    <th style="padding: 15px;">Value (LKR)</th>
                    <th style="padding: 15px; border-top-right-radius: 10px;">Current Status</th>
                  </tr>';
            
            while($row = mysqli_fetch_assoc($result)) {
                // Formatting the date
                $date = date('F j, Y, g:i a', strtotime($row['res_date']));
                
                // Status badge coloring
                $status_color = "#f39c12"; // Orange for pending
                if($row['status'] == 'Site Visit Scheduled') $status_color = "#3498db"; // Blue
                if($row['status'] == 'Completed') $status_color = "#27ae60"; // Green
                
                echo '<tr style="border-bottom: 1px solid #eee;">';
                
                // Property Info with mini thumbnail
                echo '<td style="padding: 15px; display: flex; align-items: center; gap: 15px;">
                        <img src="'.htmlspecialchars($row['image_path']).'" style="width: 60px; height: 60px; border-radius: 8px; object-fit: cover;">
                        <a href="property-details.php?id='.$row['prop_id'].'" style="color: var(--navy); font-weight: bold; text-decoration: none;">'.htmlspecialchars($row['title']).'</a>
                      </td>';
                      
                echo '<td style="padding: 15px; color: #555; font-size: 0.9rem;">'.$date.'</td>';
                echo '<td style="padding: 15px; font-weight: bold; color: var(--crimson);">Rs. '.number_format($row['price']).'</td>';
                echo '<td style="padding: 15px;">
                        <span style="background: '.$status_color.'; color: white; padding: 5px 12px; border-radius: 50px; font-size: 0.85rem; font-weight: bold;">'.$row['status'].'</span>
                      </td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<div style="text-align: center; padding: 40px;">
                    <h3 style="color: #888;">You have no active reservations.</h3>
                    <a href="properties.php" class="btn-main" style="display: inline-block; margin-top: 15px; text-decoration: none;">Browse Properties</a>
                  </div>';
        }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>