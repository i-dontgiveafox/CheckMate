<?php
session_start();
require 'config.php';

// Set proper headers
header('Content-Type: application/json');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Rate limiting (simple implementation)
$rate_limit_key = 'profile_update_' . $_SESSION['user_id'];
if (!isset($_SESSION[$rate_limit_key])) {
    $_SESSION[$rate_limit_key] = ['count' => 0, 'reset_time' => time() + 300]; // 5 minutes
}

// Reset counter if time window has passed
if (time() > $_SESSION[$rate_limit_key]['reset_time']) {
    $_SESSION[$rate_limit_key] = ['count' => 0, 'reset_time' => time() + 300];
}

// Check rate limit (max 5 updates per 5 minutes)
if ($_SESSION[$rate_limit_key]['count'] >= 5) {
    http_response_code(429);
    echo json_encode(['success' => false, 'message' => 'Too many update attempts. Please try again later.']);
    exit();
}

// Increment counter
$_SESSION[$rate_limit_key]['count']++;

try {
    $user_id = $_SESSION['user_id'];
    
    // Get and validate input data with proper sanitization
    $first_name = trim($_POST['firstName'] ?? '');
    $last_name = trim($_POST['lastName'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    
    // Enhanced validation
    if (empty($first_name) || empty($last_name) || empty($email)) {
        throw new Exception('First name, last name, and email are required.');
    }
    
    // Validate name fields (only letters, spaces, hyphens, apostrophes)
    if (!preg_match("/^[a-zA-Z\s\-']{2,50}$/", $first_name)) {
        throw new Exception('First name must be 2-50 characters and contain only letters, spaces, hyphens, and apostrophes.');
    }
    
    if (!preg_match("/^[a-zA-Z\s\-']{2,50}$/", $last_name)) {
        throw new Exception('Last name must be 2-50 characters and contain only letters, spaces, hyphens, and apostrophes.');
    }
    
    // Validate email format and length
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100) {
        throw new Exception('Invalid email format or email too long.');
    }

    // FIXED: Philippine phone number validation - strictly 11 digits only
    if (!empty($phone)) {
        // Remove all non-digit characters for validation
        $phone_digits = preg_replace('/\D/', '', $phone);
        
        // Philippine mobile number validation - exactly 11 digits starting with 09
        if (strlen($phone_digits) !== 11) {
            throw new Exception('Phone number must be exactly 11 digits.');
        }
        
        if (!preg_match('/^09\d{9}$/', $phone_digits)) {
            throw new Exception('Phone number must start with 09 followed by 9 more digits (e.g., 09171234567).');
        }
        
        // Store the cleaned phone number (digits only)
        $phone = $phone_digits;
    }
    
    // Additional email security checks
    $email_lower = strtolower($email);
    $suspicious_patterns = ['.exe', '.bat', '.cmd', '.scr', 'javascript:', 'data:', 'vbscript:'];
    foreach ($suspicious_patterns as $pattern) {
        if (strpos($email_lower, $pattern) !== false) {
            throw new Exception('Invalid email format.');
        }
    }
    
    // Start transaction for data consistency
    $conn->begin_transaction();
    
    try {
        // Check if email already exists for other users (with proper indexing)
        $stmt = $conn->prepare("SELECT user_id FROM user WHERE user_email = ? AND user_id != ? LIMIT 1");
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception('Email already exists for another user.');
        }
        $stmt->close();
        
        // Get current user data for comparison
        $stmt = $conn->prepare("SELECT user_firstname, user_lastname, user_email, user_contact FROM user WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $current_data = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        
        if (!$current_data) {
            throw new Exception('User not found.');
        }
        
        // Update user data
        $stmt = $conn->prepare("UPDATE user SET 
            user_firstname = ?, 
            user_lastname = ?, 
            user_email = ?, 
            user_contact = ?,
            updated_at = NOW()
            WHERE user_id = ?");
        
        $stmt->bind_param("ssssi", $first_name, $last_name, $email, $phone, $user_id);
        
        if (!$stmt->execute()) {
            throw new Exception('Failed to update profile: Database error occurred.');
        }
        
        if ($stmt->affected_rows === 0) {
            throw new Exception('No changes were made to your profile.');
        }
        
        $stmt->close();
        
        $conn->commit();
        
        // Update session data
        $_SESSION['user_name'] = $first_name;
        
        // Reset rate limit counter on successful update
        $_SESSION[$rate_limit_key]['count'] = 0;
        
        echo json_encode([
            'success' => true, 
            'message' => 'Profile updated successfully!',
            'data' => [
                'firstName' => htmlspecialchars($first_name, ENT_QUOTES, 'UTF-8'),
                'lastName' => htmlspecialchars($last_name, ENT_QUOTES, 'UTF-8'),
                'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
                'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8')
            ]
        ]);
        
    } catch (Exception $e) {
        // Rollback transaction on error
        $conn->rollback();
        throw $e;
    }
    
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>