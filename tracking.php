<?php 
include('header.php'); 
if(!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }
$uid = $_SESSION['user_id'];

// Get the latest acquisition for the user
$order_sql = "SELECT o.order_id, p.title FROM orders o JOIN properties p ON o.prop_id = p.prop_id WHERE o.user_id = $uid ORDER BY o.order_date DESC LIMIT 1";
$order_res = mysqli_query($conn, $order_sql);
$order = mysqli_fetch_assoc($order_res);
?>

<div class="container" style="margin-top: 60px; max-width: 850px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
        <?php if($order): 
            $oid = $order['order_id'];
            $track_res = mysqli_query($conn, "SELECT * FROM tracking WHERE order_id = $oid ORDER BY step_number DESC LIMIT 1");
            $status = mysqli_fetch_assoc($track_res);
            $current_step = $status['step_number'] ?? 1;
        ?>
            <h2 style="color: var(--navy); margin-bottom: 5px;">Tracking: <?php echo $order['title']; ?></h2>
            <p style="color: #888; margin-bottom: 40px;">Order ID: #ES-<?php echo $oid; ?></p>

            <div style="display: flex; justify-content: space-between; position: relative; margin-bottom: 50px;">
                <div style="position: absolute; top: 20px; left: 0; width: 100%; height: 3px; background: #eee; z-index: 1;"></div>
                <?php 
                $steps = ["Inquiry", "Valuation", "Legal Vetting", "Deed Transfer"];
                foreach($steps as $index => $label): 
                    $num = $index + 1;
                    $isActive = ($num <= $current_step);
                    $color = $isActive ? 'var(--navy)' : '#ccc';
                ?>
                    <div style="text-align: center; z-index: 2; width: 25%;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: <?php echo $color; ?>; color: white; line-height: 40px; margin: 0 auto 10px; font-weight: bold; border: 4px solid white;"><?php echo $num; ?></div>
                        <span style="font-size: 0.85rem; color: <?php echo $color; ?>; font-weight: bold;"><?php echo $label; ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div style="background: var(--light); padding: 25px; border-radius: 12px; border-left: 6px solid var(--navy);">
                <h4 style="margin: 0; color: var(--navy);">Status Update:</h4>
                <p style="margin: 10px 0; color: #555;"><?php echo $status['status_desc'] ?? "Awaiting initial agent verification."; ?></p>
                <small style="color: #aaa;">Last updated: <?php echo $status['updated_at'] ?? "Just now"; ?></small>
            </div>
        <?php else: ?>
            <p style="text-align:center;">No active property acquisitions found.</p>
        <?php endif; ?>
    </div>
</div>
<?php include('footer.php'); ?>