<?php include('header.php'); ?>
<div class="container" style="margin-top: 50px;">
    <h2>Purchase & Deposit History</h2>
    <table style="width:100%; border-collapse: collapse; background: white; box-shadow: var(--shadow);">
        <thead>
            <tr style="background: var(--navy); color: white;">
                <th style="padding: 15px; text-align: left;">Order ID</th>
                <th style="padding: 15px; text-align: left;">Date</th>
                <th style="padding: 15px; text-align: left;">Property</th>
                <th style="padding: 15px; text-align: left;">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $uid = $_SESSION['user_id'];
            $orders = mysqli_query($conn, "SELECT o.*, p.title FROM orders o JOIN properties p ON o.prop_id = p.prop_id WHERE o.user_id = $uid");
            while($row = mysqli_fetch_assoc($orders)) {
                echo "<tr>
                    <td style='padding:15px; border-bottom:1px solid #eee;'>#ES-{$row['order_id']}</td>
                    <td style='padding:15px; border-bottom:1px solid #eee;'>{$row['order_date']}</td>
                    <td style='padding:15px; border-bottom:1px solid #eee;'>{$row['title']}</td>
                    <td style='padding:15px; border-bottom:1px solid #eee;'><span style='color:var(--success)'>{$row['status']}</span></td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php include('footer.php'); ?>