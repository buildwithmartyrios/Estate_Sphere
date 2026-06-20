<?php 
include('db_config.php'); 
include('header.php'); 

// STRICT SECURITY CHECK: Allow BOTH 'admin' and 'super_admin'
if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) { 
    echo "<script>alert('Access Denied. Authorized Personnel Only.'); window.location.href = 'index.php';</script>";
    exit(); 
}

// Handle Reservation Status Updates
if(isset($_POST['update_status'])) {
    $res_id = mysqli_real_escape_string($conn, $_POST['res_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['new_status']);
    mysqli_query($conn, "UPDATE reservations SET status = '$new_status' WHERE res_id = '$res_id'");
    echo "<script>window.location.href = 'admin-dashboard.php';</script>";
}

// Analytics Queries
$prop_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM properties WHERE status = 'available'"))['total'];
$res_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM reservations WHERE status != 'Completed'"))['total'];

// Calculate total potential revenue
$rev_query = mysqli_query($conn, "SELECT SUM(p.price) as total_rev FROM reservations r JOIN properties p ON r.prop_id = p.prop_id WHERE r.status != 'Completed'");
$rev_total = mysqli_fetch_assoc($rev_query)['total_rev'] ?? 0;
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px; min-height: 60vh;">
    <h2 style="color: var(--navy); border-bottom: 2px solid var(--crimson); padding-bottom: 10px; display: inline-block;">Admin Command Center ⚙️</h2>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-top: 30px; margin-bottom: 40px;">
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid #3498db;">
            <h4 style="color: #666; margin-top: 0; font-size: 0.9rem; text-transform: uppercase;">Active Properties</h4>
            <h2 style="color: var(--navy); font-size: 2.5rem; margin: 10px 0 0 0;"><?php echo $prop_count; ?></h2>
        </div>
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid #e74c3c;">
            <h4 style="color: #666; margin-top: 0; font-size: 0.9rem; text-transform: uppercase;">Pending Actions</h4>
            <h2 style="color: var(--navy); font-size: 2.5rem; margin: 10px 0 0 0;"><?php echo $res_count; ?></h2>
        </div>
        <div style="background: white; padding: 25px; border-radius: 12px; box-shadow: var(--shadow); border-left: 5px solid #27ae60;">
            <h4 style="color: #666; margin-top: 0; font-size: 0.9rem; text-transform: uppercase;">Potential Value (LKR)</h4>
            <h2 style="color: var(--navy); font-size: 2rem; margin: 10px 0 0 0;"><?php echo number_format($rev_total / 1000000, 2); ?>M</h2>
        </div>
    </div>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow); margin-bottom: 40px;">
        <h3 style="color: var(--navy); margin-top: 0; margin-bottom: 20px;">Manage Client Reservations</h3>
        
        <?php
        $query = "SELECT r.res_id, r.res_date, r.status, p.title, p.price, u.user_id, u.email 
                  FROM reservations r 
                  JOIN properties p ON r.prop_id = p.prop_id 
                  JOIN users u ON r.user_id = u.user_id 
                  ORDER BY r.res_date DESC";
                  
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            echo '<table style="width: 100%; border-collapse: collapse; text-align: left;">';
            echo '<tr style="background: var(--navy); color: white;">
                    <th style="padding: 15px; border-top-left-radius: 10px;">Client</th>
                    <th style="padding: 15px;">Property Reserved</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px; border-top-right-radius: 10px;">Action / Status</th>
                  </tr>';
            
            while($row = mysqli_fetch_assoc($result)) {
                $date = date('M j, Y', strtotime($row['res_date']));
                echo '<tr style="border-bottom: 1px solid #eee;">';
                echo '<td style="padding: 15px;"><strong>'.htmlspecialchars($row['user_id']).'</strong><br><span style="font-size: 0.85rem; color: #666;">'.htmlspecialchars($row['email']).'</span></td>';
                echo '<td style="padding: 15px; color: var(--navy); font-weight: bold;">'.htmlspecialchars($row['title']).'</td>';
                echo '<td style="padding: 15px; color: #555;">'.$date.'</td>';
                echo '<td style="padding: 15px;">
                        <form method="POST" action="admin-dashboard.php" style="display: flex; gap: 10px;">
                            <input type="hidden" name="res_id" value="'.$row['res_id'].'">
                            <select name="new_status" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;">
                                <option value="Pending Agent" '.($row['status'] == 'Pending Agent' ? 'selected' : '').'>Pending Agent</option>
                                <option value="Site Visit Scheduled" '.($row['status'] == 'Site Visit Scheduled' ? 'selected' : '').'>Site Visit Scheduled</option>
                                <option value="Completed" '.($row['status'] == 'Completed' ? 'selected' : '').'>Completed</option>
                            </select>
                            <button type="submit" name="update_status" style="background: var(--crimson); color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-weight: bold;">Update</button>
                        </form>
                      </td>';
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<p style="color: #888; text-align: center; padding: 20px;">No active reservations found.</p>';
        }
        ?>
    </div>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="color: var(--navy); margin: 0;">Manage Property Inventory</h3>
            <a href="admin/add-property.php" class="btn-main" style="background: #27ae60; text-decoration: none; padding: 10px 20px; font-size: 0.9rem;">➕ Add New Property</a>
        </div>
        
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <tr style="background: var(--navy); color: white;">
                <th style="padding: 15px; border-top-left-radius: 10px;">ID</th>
                <th style="padding: 15px;">Property Title</th>
                <th style="padding: 15px;">Price (LKR)</th>
                <th style="padding: 15px; text-align: center; border-top-right-radius: 10px;">Actions</th>
            </tr>
            <?php
            $prop_query = mysqli_query($conn, "SELECT * FROM properties ORDER BY prop_id DESC");
            if(mysqli_num_rows($prop_query) > 0) {
                while ($prop = mysqli_fetch_assoc($prop_query)): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 15px;"><?php echo $prop['prop_id']; ?></td>
                        <td style="padding: 15px; font-weight: bold;"><?php echo htmlspecialchars($prop['title']); ?></td>
                        <td style="padding: 15px; color: var(--crimson);">Rs. <?php echo number_format($prop['price']); ?></td>
                        <td style="padding: 15px; text-align: center;">
                            <a href="admin/edit-property.php?id=<?php echo $prop['prop_id']; ?>" style="color: var(--navy); text-decoration: none; font-weight: bold; margin-right: 15px; font-size: 0.9rem;">✏️ Edit</a>
                            <a href="admin/delete-property.php?id=<?php echo $prop['prop_id']; ?>" onclick="return confirm('Are you sure you want to delete this property?');" style="color: var(--crimson); text-decoration: none; font-weight: bold; font-size: 0.9rem;">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; 
            } else {
                echo '<tr><td colspan="4" style="text-align: center; padding: 20px; color: #888;">No properties in inventory.</td></tr>';
            }
            ?>
        </table>
    </div>
</div>

<?php include('footer.php'); ?>