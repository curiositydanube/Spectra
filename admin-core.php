<?php
// Core security module - included globally
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$admin_conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($admin_conn->connect_error) {
    die("Global Admin core connection failed: " . $admin_conn->connect_error);
}

$is_admin = false;
$user_is_banned = false;
$user_upload_suspended = false;

if (isset($_SESSION['user_id'])) {
    $session_uid = (int)$_SESSION['user_id'];
    $check_query = $admin_conn->prepare("SELECT Admin, Banned, UploadSuspended FROM users WHERE id = ?");
    $check_query->bind_param("i", $session_uid);
    $check_query->execute();
    $status_res = $check_query->get_result()->fetch_assoc();
    
    if ($status_res) {
        if (strtoupper($status_res['Admin']) === 'YES') {
            $is_admin = true;
        }
        if (strtoupper($status_res['Banned']) === 'YES') {
            $user_is_banned = true;
        }
        if (strtoupper($status_res['UploadSuspended']) === 'YES') {
            $user_upload_suspended = true;
        }
    }
    $check_query->close();
}

// Global Enforcement Rule 1: Terminate session instantly if banned
if ($user_is_banned && !isset($bypass_ban_check)) {
    session_destroy();
    die("<div style='padding:50px; text-align:center; font-family:Arial;'><h2>Account Terminated</h2>This user account has been permanently banned from SpectraVideo networks for violating community terms.</div>");
}

// Global Administration Actions Handler
if ($is_admin && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['global_admin_action'])) {
    $action_target = (int)$_POST['target_user_id'];
    $action_type = $_POST['action_type'];
    
    if ($action_type === 'ban') {
        $act_stmt = $admin_conn->prepare("UPDATE users SET Banned = 'YES' WHERE id = ?");
    } elseif ($action_type === 'unban') {
        $act_stmt = $admin_conn->prepare("UPDATE users SET Banned = 'NO' WHERE id = ?");
    } elseif ($action_type === 'suspend_upload') {
        $act_stmt = $admin_conn->prepare("UPDATE users SET UploadSuspended = 'YES' WHERE id = ?");
    } elseif ($action_type === 'unsuspend_upload') {
        $act_stmt = $admin_conn->prepare("UPDATE users SET UploadSuspended = 'NO' WHERE id = ?");
    }
    
    if (isset($act_stmt)) {
        $act_stmt->bind_param("i", $action_target);
        $act_stmt->execute();
        $act_stmt->close();
        
        // Refresh page to show status state updates
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit();
    }
}
$admin_conn->close();
?>