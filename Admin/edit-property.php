<?php
// 1. Force the database connection and header using correct paths
require_once('../db_config.php');
require_once('../header.php');

// 2. Ensure connection exists
if (!isset($conn) || $conn === null) {
    die("Database connection failed. Please check db_config.php.");
}

// STRICT SECURITY CHECK
if(!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo "<script>alert('Access Denied. Authorized Personnel Only.'); window.location.href='../index.php';</script>";
    exit();
}

// Ensure an ID was passed in the URL
if (!isset($_GET['id'])) {
    echo "<script>alert('No property selected!'); window.location.href='../admin-dashboard.php';</script>";
    exit();
}

$prop_id = mysqli_real_escape_string($conn, $_GET['id']);

// Fetch existing property details
$fetch_query = "SELECT * FROM properties WHERE prop_id = '$prop_id'";
$result = mysqli_query($conn, $fetch_query);
$property = mysqli_fetch_assoc($result);

if (!$property) {
    echo "<script>alert('Property not found!'); window.location.href='../admin-dashboard.php';</script>";
    exit();
}

// Handle Form Submission
if (isset($_POST['update_property'])) {
    // 1. Capture and sanitize text inputs
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $desc = mysqli_real_escape_string($conn, $_POST['description']);
    $map_link = mysqli_real_escape_string($conn, $_POST['map_link']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // 2. Strictly cast numerical inputs
    $price = !empty($_POST['price']) ? (float)$_POST['price'] : 0;
    $bedrooms = !empty($_POST['bedrooms']) ? (int)$_POST['bedrooms'] : 0;
    $bathrooms = !empty($_POST['bathrooms']) ? (int)$_POST['bathrooms'] : 0;
    $area = !empty($_POST['area_sqft']) ? (int)$_POST['area_sqft'] : 0;

    // Keep existing files as default
    $image_path = $property['image_path'];
    $img_name = $property['image_name'];
    $verification_doc = isset($property['verification_doc']) ? $property['verification_doc'] : "";
    $is_verified = isset($property['is_verified']) ? $property['is_verified'] : 0;

    // 3. Image File Upload Logic (Check if a NEW image was uploaded)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $new_img_name = $_FILES['image']['name'];
        $img_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = '../uploads/';
        
        if(!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        $unique_img_name = uniqid() . "_" . basename($new_img_name);
        
        if (move_uploaded_file($img_tmp, $upload_dir . $unique_img_name)) {
            $image_path = 'uploads/' . $unique_img_name;
            $img_name = $unique_img_name;
        }
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

    // 5. Update Database
    $update_sql = "UPDATE properties SET 
                    title = '$title', 
                    price = '$price', 
                    location = '$location', 
                    description = '$desc', 
                    bedrooms = '$bedrooms', 
                    bathrooms = '$bathrooms', 
                    area_sqft = '$area', 
                    image_path = '$image_path', 
                    image_name = '$img_name', 
                    map_link = '$map_link',
                    status = '$status',
                    is_verified = '$is_verified',
                    verification_doc = '$verification_doc'
                   WHERE prop_id = '$prop_id'";
                   
    if (mysqli_query($conn, $update_sql)) {
        echo "<script>alert('Property Updated Successfully!'); window.location.href='../admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Database Error: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "');</script>";
    }
}
?>

<div class="container" style="margin-top: 80px; max-width: 600px; margin-bottom: 80px;">
    <div style="background: white; padding: 40px; border-radius: 15px; box-shadow: var(--shadow);">
        <h2 style="color: var(--navy); margin-top: 0; margin-bottom: 20px;">Edit Property #<?php echo $prop_id; ?></h2>
        
        <form method="POST" enctype="multipart/form-data">
            
            <label style="font-weight: bold; color: #555;">Property Title:</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($property['title']); ?>" required>
            
            <label style="font-weight: bold; color: #555;">Price (LKR):</label>
            <input type="number" name="price" class="form-control" value="<?php echo htmlspecialchars($property['price']); ?>" required>
            
            <label style="font-weight: bold; color: #555;">Location:</label>
            <input type="text" name="location" class="form-control" value="<?php echo htmlspecialchars($property['location']); ?>" required>
            
            <div style="display: flex; gap: 15px;">
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Bedrooms:</label>
                    <input type="number" name="bedrooms" class="form-control" value="<?php echo htmlspecialchars($property['bedrooms']); ?>" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Bathrooms:</label>
                    <input type="number" name="bathrooms" class="form-control" value="<?php echo htmlspecialchars($property['bathrooms']); ?>" required>
                </div>
                <div style="flex: 1;">
                    <label style="font-weight: bold; color: #555;">Area (sqft):</label>
                    <input type="number" name="area_sqft" class="form-control" value="<?php echo htmlspecialchars($property['area_sqft']); ?>" required>
                </div>
            </div>
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Property Description:</label>
            <textarea name="description" class="form-control" rows="4"><?php echo htmlspecialchars($property['description']); ?></textarea>
            
            <label style="font-weight: bold; color: #555; margin-top: 10px;">Status:</label>
            <select name="status" class="form-control">
                <option value="available" <?php if($property['status'] == 'available') echo 'selected'; ?>>Available</option>
                <option value="sold" <?php if($property['status'] == 'sold') echo 'selected'; ?>>Sold</option>
            </select>

            <div style="background: #f9f9f9; padding: 15px; border-radius: 8px; margin-top: 15px; border: 1px solid #ddd;">
                <label style="font-weight: bold; color: #555;">Update Property Image (Optional):</label>
                <p style="font-size: 0.85rem; color: #777; margin-top: 0;">Leave this blank to keep the current image.</p>
                <input type="file" name="image" class="form-control" accept="image/*">
                
                <?php if(!empty($property['image_path'])): ?>
                    <p style="font-size: 0.85rem; color: #555;">Current Image: <br>
                    <img src="../<?php echo htmlspecialchars($property['image_path']); ?>" style="width: 150px; border-radius: 5px; margin-top: 5px;"></p>
                <?php endif; ?>
            </div>
            
            <label style="font-weight: bold; color: #555; margin-top: 15px;">Google Maps Embed URL:</label>
            <textarea name="map_link" class="form-control" rows="3"><?php echo htmlspecialchars($property['map_link']); ?></textarea>

            <label style="font-weight: bold; color: #555; margin-top: 15px;">Update Legal Deed Clearance Vetting (PDF Only):</label>
            <input type="file" name="verification_doc" class="form-control" accept=".pdf">
            
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="submit" name="update_property" class="btn-main" style="flex: 2; padding: 15px; background: #27ae60;">💾 Save Changes</button>
                <a href="../admin-dashboard.php" class="btn-main" style="flex: 1; padding: 15px; background: #95a5a6; text-decoration: none; text-align: center;">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once('../footer.php'); ?>