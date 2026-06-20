<?php include('header.php'); ?>

<?php
// Catch the contact form submission
if(isset($_POST['send_message'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $insert = mysqli_query($conn, "INSERT INTO inquiries (name, email, phone, message) VALUES ('$name', '$email', '$phone', '$message')");

    if($insert) {
        echo "<script>alert('Thank you! Your message has been sent to our agents.'); window.location.href = 'contact.php';</script>";
        exit();
    }
}
?>

<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    
    <?php
    // Form Submission Logic
    if(isset($_POST['send_msg'])) {
        // Assuming $conn is established in header.php or db_connect.php
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $msg = mysqli_real_escape_string($conn, $_POST['message']);

        $sql = "INSERT INTO inquiries (name, email, message) VALUES ('$name', '$email', '$msg')";
        if(mysqli_query($conn, $sql)) {
            echo "<div style='padding:20px; background:#d4edda; color:#155724; border-radius:10px; margin-bottom:30px; text-align:center; font-weight:bold; box-shadow: var(--shadow);'>
                    ✅ Message sent successfully! Our agents will contact you shortly.
                  </div>";
        } else {
            echo "<div style='padding:20px; background:#f8d7da; color:#721c24; border-radius:10px; margin-bottom:30px; text-align:center; font-weight:bold;'>
                    Error sending message: " . mysqli_error($conn) . "
                  </div>";
        }
    }
    ?>

    <div style="display: flex; gap: 40px; flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px; background: var(--navy); color: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
            <h2 style="color: white; margin-top: 0; margin-bottom: 20px;">Contact Information</h2>
            <p style="color: #eee; margin-bottom: 30px; line-height: 1.8;">
                Have questions about premium properties in Sri Lanka? Reach out to our specialist helpdesk team directly.
            </p>
            
            <div style="display: flex; flex-direction: column; gap: 20px;">
                <p style="margin: 0; font-size: 1.1rem;"><strong>📞 Phone:</strong> <br><span style="color: #ccc; font-size: 1rem;">+94 66 223 4567</span></p>
                <p style="margin: 0; font-size: 1.1rem;"><strong>✉️ Email:</strong> <br><span style="color: #ccc; font-size: 1rem;">support@estatesphere.lk</span></p>
                <p style="margin: 0; font-size: 1.1rem;"><strong>📍 Office:</strong> <br><span style="color: #ccc; font-size: 1rem;">Aluwihara, Matale, Sri Lanka</span></p>
                <p style="margin: 0; font-size: 1.1rem;"><strong>⏰ Business Hours:</strong> <br><span style="color: #ccc; font-size: 1rem;">Mon - Sat: 8:00 AM - 5:00 PM</span></p>
            </div>
        </div>
        
        <div style="flex: 1; min-width: 300px; background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
            <h3 style="color: var(--navy); margin-top: 0; margin-bottom: 20px;">Send us a Message</h3>
            <form method="POST">
                <label style="font-weight: bold; color: #555; font-size: 0.9rem;">Your Name</label>
                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                
                <label style="font-weight: bold; color: #555; font-size: 0.9rem; margin-top: 10px; display: block;">Your Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                
                <label style="font-weight: bold; color: #555; font-size: 0.9rem; margin-top: 10px; display: block;">How can we help?</label>
                <textarea name="message" class="form-control" rows="5" placeholder="Which property are you interested in?" required></textarea>
                
                <button type="submit" name="send_msg" class="btn-main" style="margin-top: 15px; width: 100%; padding: 15px; font-size: 1.1rem;">Send Message</button>
            </form>
        </div>
        
    </div>
</div>

<?php include('footer.php'); ?>