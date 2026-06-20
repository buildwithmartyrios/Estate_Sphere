<?php 
// 1. Force the database connection and header
require_once('../db_config.php'); 
require_once('../header.php');

// 2. Ensure connection exists
if (!isset($conn) || $conn === null) {
    die("Database connection failed. Please check db_config.php.");
}

// STRICT SECURITY CHECK: Allow BOTH 'admin' and 'super_admin'
if(!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo "<script>alert('Access Denied. Authorized Personnel Only.'); window.location.href='../index.php';</script>";
    exit();
}

if(isset($_POST['add_property'])) {
    // 1. Capture and sanitize text inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $map_link = mysqli_real_escape_string($conn, $_POST['map_link']);
    
    // 2. Strictly cast numerical inputs
    $price = !empty($_POST['price']) ? (float)$_POST['price'] : 0;
    $bedrooms = !empty($_POST['bedrooms']) ? (int)$_POST['bedrooms'] : 0;
    $bathrooms = !empty($_POST['bathrooms']) ? (int)$_POST['bathrooms'] : 0;
    $area = !empty($_POST['area_sqft']) ? (int)$_POST['area_sqft'] : 0;
    
    // Default values
    $is_verified = 0;
    $verification_doc = "";
    $image_path = ""; 
    $img_name = "";

    // 3. Image File Upload Logic (WITH SAFETY CHECK)
    // Note the path change: We want uploads to go to estate_sphere/uploads, not estate_sphere/admin/uploads
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $img_name = $_FILES['image']['name'];
        $img_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = '../uploads/'; 
        
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $unique_img_name = uniqid() . "_" . basename($img_name);
        
        if (move_uploaded_file($img_tmp, $upload_dir . $unique_img_name)) {
             $image_path = 'uploads/' . $unique_img_name; 
             $img_name = $unique_img_name;
        } else {
             echo "<script>alert('Error uploading image.');</script>";
        }
    } else {
        echo "<script>alert('Please select an image to upload.');</script>";
    }

    // 4. Legal PDF Document Upload Logic
    if (isset($_FILES['verification_doc']) && $_FILES['verification_doc']['error'] == 0) {
        $doc_dir = '../uploads/docs/';
        
        if (!is_dir($doc_dir)) {
            mkdir($doc_dir, 0777, true);
        }
        
        $file_ext = strtolower(pathinfo($_FILES['verification_doc']['name'], PATHINFO_EXTENSION));
        
        if ($file_ext === 'pdf') {
            $new_doc_name = "DEED_" . uniqid() . ".pdf";
            
            if (move_uploaded_file($_FILES['verification_doc']['tmp_name'], $doc_dir . $new_doc_name)) {
                $verification_doc = "uploads/docs/" . $new_doc_name;
                $is_verified = 1; 
            }
        }
    }

    // 5. Only insert if we have an image path
    if (!empty($image_path)) {
        $sql = "INSERT INTO properties (title, price, location, description, bedrooms, bathrooms, area_sqft, image_path, image_name, map_link, is_verified, verification_doc, status) 
                VALUES ('$title', '$price', '$location', '$desc', '$bedrooms', '$bathrooms', '$area', '$image_path', '$img_name', '$map_link', '$is_verified', '$verification_doc', 'available')";
        
        if(mysqli_query($conn, $sql)) {
            echo "<script>alert('Property Added Successfully!'); window.location.href='../admin-dashboard.php';</script>";
        } else {
            echo "<script>alert('Database Error: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "');</script>";
        }
    }
}
?>

<div class="container" style="margin-top: 80px; max-width: 600px; margin-bottom: 80px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
        <h2 style="color: var(--navy); margin-top: 0; margin-bottom: 20px;">Add New Property</h2>
        <form method="POST" enctype="multipart/form-data">
            
            <label style="font-weight: bold; color: #555;">Property Title:</label>
            <input type="text" name="title" class="form-control" placeholder="e.g., Luxury Villa in Matale" required>
            
            <label style="font-weight: bold; color: #555;">Price (LKR):</label>
            <input type="number" name="price" class="form-control" placeholder="Price (LKR)" required>
            
            <label style="font-weight: bold; color: #555;">Location:</label>
            <input type="text" name="location" class="form-control" placeholder="e.g., Godapola Road, Matale" required>
            
            <div style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Bedrooms:</label>
                    <input type="number" name="bedrooms" class="form-control" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Bathrooms:</label>
                    <input type="number" name="bathrooms" class="form-control" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Area (sqft):</label>
                    <input type="number" name="area_sqft" class="form-control" required>
                </div>
            </div>
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Property Description:</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Describe the premium features of this property..."></textarea>
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Upload Property Image:</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Upload Legal Deed Clearance Vetting (PDF Only):</label>
            <input type="file" name="verification_doc" class="form-control" accept=".pdf">
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Google Maps Embed URL:</label>
            <textarea name="map_link" class="form-control" rows="3" placeholder="Paste map embed link here..."></textarea>
            
            <button type="submit" name="add_property" class="btn-main" style="width: 100%; margin-top: 20px; padding: 15px;">Publish Listing</button>
        </form>
    </div>
</div>

<?php require_once('../footer.php'); ?>