<?php
// 1. Force the database connection using correct paths
require_once('../db_config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Ensure connection exists
if (!isset($conn) || $conn === null) {
    die("Database connection failed. Please check db_config.php.");
}

// 3. STRICT SECURITY CHECK
if(!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')) {
    echo "<script>alert('Access Denied. Authorized Personnel Only.'); window.location.href='../index.php';</script>";
    exit();
}

if(isset($_GET['id'])) {
    $prop_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // 4. Fetch the file paths and store them, BUT DO NOT DELETE THEM YET
    $image_to_delete = "";
    $doc_to_delete = "";
    
    $file_query = mysqli_query($conn, "SELECT image_path, verification_doc FROM properties WHERE prop_id = '$prop_id'");
    if($file_row = mysqli_fetch_assoc($file_query)) {
        $image_to_delete = $file_row['image_path'];
        $doc_to_delete = $file_row['verification_doc'];
    }

    // 5. Delete from Child Tables FIRST to satisfy Foreign Key Constraints
    mysqli_query($conn, "DELETE FROM cart WHERE prop_id = '$prop_id'");
    mysqli_query($conn, "DELETE FROM favourites WHERE prop_id = '$prop_id'");
    mysqli_query($conn, "DELETE FROM reservations WHERE prop_id = '$prop_id'");

    // 6. Delete from the Parent database
    $delete_query = "DELETE FROM properties WHERE prop_id = '$prop_id'";
    
    if(mysqli_query($conn, $delete_query)) {
        // 7. CRITICAL FIX: ONLY delete the physical files IF the database row was successfully deleted!
        if(!empty($image_to_delete) && file_exists('../' . $image_to_delete)) {
            unlink('../' . $image_to_delete); 
        }
        if(!empty($doc_to_delete) && file_exists('../' . $doc_to_delete)) {
            unlink('../' . $doc_to_delete); 
        }

        echo "<script>alert('Property Deleted Successfully!'); window.location.href='../admin-dashboard.php';</script>";
    } else {
        echo "<script>alert('Error deleting property from database: " . mysqli_real_escape_string($conn, mysqli_error($conn)) . "'); window.location.href='../admin-dashboard.php';</script>";
    }
} else {
    header("Location: ../admin-dashboard.php");
    exit();
}
?>