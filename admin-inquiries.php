<?php 
include('db_config.php'); 
include('header.php'); 

// STRICT SECURITY CHECK: Allow BOTH 'admin' and 'super_admin'
if(!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) { 
    echo "<script>alert('Access Denied. Authorized Personnel Only.'); window.location.href = 'index.php';</script>";
    exit(); 
}

// Handle Mark as Resolved
if(isset($_POST['mark_resolved'])) {
    $msg_id = mysqli_real_escape_string($conn, $_POST['msg_id']);
    mysqli_query($conn, "UPDATE inquiries SET status = 'Resolved' WHERE msg_id = '$msg_id'");
    echo "<script>window.location.href = 'admin-inquiries.php';</script>";
}
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px; min-height: 60vh;">
    <h2 style="color: var(--navy); border-bottom: 2px solid var(--crimson); padding-bottom: 10px; display: inline-block;">Admin Inbox 📥</h2>
    <p style="color: #666; margin-bottom: 30px;">Manage and respond to client inquiries from the Contact page.</p>

    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
        
        <?php
        $query = "SELECT * FROM inquiries ORDER BY status DESC, created_at DESC"; // Puts 'Unread' at the top
        $result = mysqli_query($conn, $query);

        if(mysqli_num_rows($result) > 0) {
            echo '<table style="width: 100%; border-collapse: collapse; text-align: left;">';
            echo '<tr style="background: var(--navy); color: white;">
                    <th style="padding: 15px; border-top-left-radius: 10px;">Client</th>
                    <th style="padding: 15px;">Message</th>
                    <th style="padding: 15px;">Date</th>
                    <th style="padding: 15px; border-top-right-radius: 10px;">Status</th>
                  </tr>';
            
            while($row = mysqli_fetch_assoc($result)) {
                $date = date('M j, Y - g:i a', strtotime($row['created_at']));
                $is_unread = ($row['status'] === 'Unread');
                
                // Highlight unread messages with a light blue background
                $bg_color = $is_unread ? '#f0f8ff' : '#ffffff';
                
                echo '<tr style="border-bottom: 1px solid #eee; background: '.$bg_color.';">';
                
                // Client Info
                echo '<td style="padding: 15px; width: 200px;">
                        <strong>'.htmlspecialchars($row['name']).'</strong><br>
                        <span style="font-size: 0.85rem; color: #0066cc;">'.htmlspecialchars($row['email']).'</span><br>
                        <span style="font-size: 0.85rem; color: #666;">📞 '.htmlspecialchars($row['phone']).'</span>
                      </td>';
                      
                // The Message
                echo '<td style="padding: 15px; max-width: 300px; color: #444; font-size: 0.95rem; line-height: 1.5;">'
                        .nl2br(htmlspecialchars($row['message'])).
                     '</td>';
                
                // Date
                echo '<td style="padding: 15px; color: #666; font-size: 0.85rem;">'.$date.'</td>';
                
                // Status / Action
                echo '<td style="padding: 15px; text-align: center;">';
                if($is_unread) {
                    echo '<span style="display:inline-block; margin-bottom:10px; background:#e74c3c; color:white; padding:4px 10px; border-radius:50px; font-size:0.8rem; font-weight:bold;">Unread</span>';
                    echo '<form method="POST">
                            <input type="hidden" name="msg_id" value="'.$row['msg_id'].'">
                            <button type="submit" name="mark_resolved" style="background:#27ae60; color:white; border:none; padding:8px 15px; border-radius:5px; cursor:pointer; font-size: 0.85rem;">✔ Mark Resolved</button>
                          </form>';
                } else {
                    echo '<span style="background:#95a5a6; color:white; padding:4px 10px; border-radius:50px; font-size:0.8rem; font-weight:bold;">Resolved</span>';
                }
                echo '</td>';
                
                echo '</tr>';
            }
            echo '</table>';
        } else {
            echo '<div style="text-align: center; padding: 40px; color: #888;">
                    <h3>Your inbox is empty!</h3>
                    <p>No client inquiries have been submitted yet.</p>
                  </div>';
        }
        ?>
    </div>
</div>

<?php include('footer.php'); ?>