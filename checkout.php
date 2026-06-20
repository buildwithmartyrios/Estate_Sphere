<?php 
include('header.php'); 

// Security check: Must be logged in
if(!isset($_SESSION['user_id'])) { 
    echo "<script>alert('Please login to checkout.'); window.location.href = 'login.php';</script>";
    exit(); 
}

$user_id = $_SESSION['user_id'];

// Check if cart has items
$cart_check = mysqli_query($conn, "SELECT * FROM cart WHERE user_id = '$user_id'");
if(mysqli_num_rows($cart_check) == 0 && !isset($_POST['place_order'])) {
    echo "<script>alert('Your cart is empty!'); window.location.href = 'cart.php';</script>";
    exit();
}

// Handle Order Placement & Email Generation
if(isset($_POST['place_order'])) {
    $fname = mysqli_real_escape_string($conn, $_POST['fname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // 1. Fetch Cart Items BEFORE clearing the cart to build the email receipt
    $cart_summary = "";
    $fetch_items = mysqli_query($conn, "SELECT p.title, p.price FROM properties p JOIN cart c ON p.prop_id = c.prop_id WHERE c.user_id = '$user_id'");
    while($row = mysqli_fetch_assoc($fetch_items)) {
        $cart_summary .= "<li><strong>" . htmlspecialchars($row['title']) . "</strong> - Rs. " . number_format($row['price']) . "</li>";
    }

    // 2. Save to Reservations Table BEFORE clearing the cart
    $save_res = mysqli_query($conn, "SELECT prop_id FROM cart WHERE user_id = '$user_id'");
    while($res_row = mysqli_fetch_assoc($save_res)) {
        $property_id = $res_row['prop_id'];
        mysqli_query($conn, "INSERT INTO reservations (user_id, prop_id, status) VALUES ('$user_id', '$property_id', 'Pending Agent')");
    }

    // 3. Now it is safe to Clear the Cart
    mysqli_query($conn, "DELETE FROM cart WHERE user_id = '$user_id'");
    
    // 4. Prepare the Email Content
    $email_subject = "Property Reservation Confirmed - Estate Sphere";
    $email_body = "
        <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #ddd; padding: 20px; border-radius: 10px;'>
            <h2 style='color: #1a2a6c;'>Reservation Acknowledgment</h2>
            <p>Dear $fname,</p>
            <p>Thank you for choosing Estate Sphere. We have officially received your expression of interest for the following properties:</p>
            <ul>$cart_summary</ul>
            <h3 style='color: #b21f1f; margin-top: 20px;'>Next Steps:</h3>
            <p>Our Senior Real Estate Agent, <strong>Mr. Kamal Perera</strong>, has been assigned to your profile. He will contact you within the next 24 hours at the phone number you provided to schedule a physical site visit and guide you through the legal deed verification process.</p>
            <p>For urgent inquiries, please reply to this email or call our hotline at <strong>+94 66 223 4567</strong>.</p>
            <hr style='border: none; border-top: 1px solid #eee; margin: 20px 0;'>
            <p style='font-size: 0.8rem; color: #777;'>Estate Sphere | Premium Real Estate Sri Lanka</p>
        </div>
    ";

    // 5. Execute standard PHP mail function
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: reservations@estatesphere.lk" . "\r\n";
    @mail($email, $email_subject, $email_body, $headers); 

    // 6. Display the Success Screen & Email Preview
    echo "<div class='container' style='margin-top: 50px; margin-bottom: 100px;'>
            <div style='background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow); max-width: 700px; margin: 0 auto; border-top: 5px solid #27ae60; text-align: center;'>
                <h1 style='color: #27ae60; font-size: 4rem; margin: 0;'>✅</h1>
                <h2 style='color: var(--navy);'>Reservation Successful!</h2>
                <p style='color: #555; font-size: 1.1rem; margin-bottom: 30px;'>Your property hold has been placed. An official confirmation email has been sent to <strong>$email</strong>.</p>
                
                <div style='background: #f9f9f9; padding: 20px; border-radius: 10px; text-align: left; margin-bottom: 30px; border: 1px dashed #ccc;'>
                    <p style='font-size: 0.85rem; color: #888; text-transform: uppercase; margin-top: 0;'>Inbox Preview (For Demo Purposes)</p>
                    $email_body
                </div>

                <a href='index.php' class='btn-main' style='text-decoration: none; padding: 12px 40px;'>Return to Homepage</a>
            </div>
          </div>";
    include('footer.php');
    exit();
}
?>

<div class="container" style="margin-top: 50px; margin-bottom: 80px; min-height: 50vh;">
    <h2 style="color: var(--navy); border-bottom: 2px solid var(--crimson); padding-bottom: 10px; display: inline-block;">Secure Property Reservation 🔒</h2>
    
    <div style="display: flex; gap: 40px; flex-wrap: wrap; margin-top: 20px;">
        
        <div style="flex: 2; min-width: 300px; background: white; padding: 30px; border-radius: 15px; box-shadow: var(--shadow);">
            <h3 style="color: var(--navy); margin-top: 0; margin-bottom: 20px;">Client Information</h3>
            <form method="POST" action="checkout.php">
                
                <div style="display: flex; gap: 15px;">
                    <div style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">First Name</label>
                        <input type="text" name="fname" class="form-control" required>
                    </div>
                    <div style="flex: 1;">
                        <label style="font-weight: bold; color: #555;">Last Name</label>
                        <input type="text" name="lname" class="form-control" required>
                    </div>
                </div>

                <label style="font-weight: bold; color: #555;">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="Where should we send your receipt?" required>

                <label style="font-weight: bold; color: #555;">Phone Number</label>
                <input type="text" name="phone" class="form-control" placeholder="+94 7X XXX XXXX" required>

                <label style="font-weight: bold; color: #555;">Permanent Address</label>
                <textarea name="address" class="form-control" rows="3" required></textarea>

                <label style="font-weight: bold; color: #555;">NIC / Passport Number</label>
                <input type="text" name="nic" class="form-control" placeholder="Required for legal vetting" required>

                <button type="submit" name="place_order" class="btn-main" style="width: 100%; padding: 15px; font-size: 1.1rem; margin-top: 20px; background: #27ae60;">
                    Confirm Reservation & Request Agent
                </button>
            </form>
        </div>

        <div style="flex: 1; min-width: 300px;">
            <div style="background: var(--light); padding: 30px; border-radius: 15px; box-shadow: var(--shadow); position: sticky; top: 100px; border-top: 5px solid var(--navy);">
                <h3 style="color: var(--navy); margin-top: 0; border-bottom: 1px solid #ddd; padding-bottom: 10px;">Properties to Reserve</h3>
                
                <?php
                $cart_query = "SELECT p.* FROM properties p JOIN cart c ON p.prop_id = c.prop_id WHERE c.user_id = '$user_id'";
                $res = mysqli_query($conn, $cart_query);
                
                $subtotal_value = 0;
                $current_currency = $_SESSION['currency'] ?? 'LKR';
                $exchange_rate = 0.0033;
                
                // Fetch Items
                while($item = mysqli_fetch_assoc($res)) {
                    $subtotal_value += $item['price'];
                    
                    if ($current_currency === 'USD') {
                        $item_price_display = "$ " . number_format($item['price'] * $exchange_rate, 2);
                    } else {
                        $item_price_display = "Rs. " . number_format($item['price']);
                    }
                    ?>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 10px;">
                        <span style="color: #555; font-size: 0.9rem; max-width: 60%;"><?php echo htmlspecialchars($item['title']); ?></span>
                        <span style="color: var(--crimson); font-weight: bold; font-size: 0.9rem;"><?php echo $item_price_display; ?></span>
                    </div>
                    <?php
                }
                
                // --- FEE MATH LOGIC ---
                $fee_rate = 0.005; // 0.5%
                $service_fee = $subtotal_value * $fee_rate;
                $grand_total = $subtotal_value + $service_fee;

                // Format Currency
                if ($current_currency === 'USD') {
                    $subtotal_display = "$ " . number_format($subtotal_value * $exchange_rate, 2);
                    $fee_display = "$ " . number_format($service_fee * $exchange_rate, 2);
                    $grand_total_display = "$ " . number_format($grand_total * $exchange_rate, 2);
                } else {
                    $subtotal_display = "Rs. " . number_format($subtotal_value);
                    $fee_display = "Rs. " . number_format($service_fee);
                    $grand_total_display = "Rs. " . number_format($grand_total);
                }
                ?>
                
                <div style="margin-top: 20px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 10px; color: #666;">
                        <span>Property Subtotal:</span>
                        <strong><?php echo $subtotal_display; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-bottom: 15px; color: #666; border-bottom: 1px solid #ddd; padding-bottom: 15px;">
                        <span>Processing Fee (0.5%):</span>
                        <strong style="color: var(--crimson);">+ <?php echo $fee_display; ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; font-size: 1.3rem;">
                        <strong style="color: var(--navy);">Grand Total:</strong>
                        <strong style="color: var(--navy);"><?php echo $grand_total_display; ?></strong>
                    </div>
                </div>
                
                <p style="font-size: 0.8rem; color: #777; margin-top: 20px; text-align: center;">
                    🔒 No payment will be deducted today. This simply reserves the property under your name until our agent contacts you.
                </p>
            </div>
        </div>
        
    </div>
</div>

<?php include('footer.php'); ?>