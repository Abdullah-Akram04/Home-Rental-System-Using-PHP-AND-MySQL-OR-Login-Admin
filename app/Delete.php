<?php
require '../config/config.php';

// Check if user is logged in
if(empty($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

// Check if ID and action parameters exist
if(isset($_GET['id']) && isset($_GET['act'])) {
    $id = $_GET['id'];
    $action = $_GET['act'];
    
    try {
        // Determine which table to delete from based on the action parameter
        if($action == 'ap') {
            // Delete from apartment table
            $table = 'room_rental_registrations_apartment';
        } else {
            // Delete from individual room table
            $table = 'room_rental_registrations';
        }
        
        // Check user permissions
        if($_SESSION['role'] == 'admin') {
            // Admin can delete any record
            $stmt = $connect->prepare("DELETE FROM $table WHERE id = :id");
        } else if($_SESSION['role'] == 'user') {
            // Users can only delete their own records
            $stmt = $connect->prepare("DELETE FROM $table WHERE id = :id AND user_id = :user_id");
        } else {
            throw new Exception("Unauthorized access");
        }
        
        // Execute the delete query
        if($_SESSION['role'] == 'admin') {
            $result = $stmt->execute(array(':id' => $id));
        } else {
            $result = $stmt->execute(array(
                ':id' => $id,
                ':user_id' => $_SESSION['id']
            ));
        }
        
        if($result) {
            // Check if any row was actually deleted
            if($stmt->rowCount() > 0) {
                $_SESSION['success_msg'] = "Apartment details deleted successfully!";
            } else {
                $_SESSION['error_msg'] = "No record found or you don't have permission to delete this record.";
            }
        } else {
            $_SESSION['error_msg'] = "Failed to delete the record.";
        }
        
    } catch(PDOException $e) {
        $_SESSION['error_msg'] = "Database error: " . $e->getMessage();
    } catch(Exception $e) {
        $_SESSION['error_msg'] = $e->getMessage();
    }
} else {
    $_SESSION['error_msg'] = "Invalid request parameters.";
}

// Redirect back to the listing page
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>