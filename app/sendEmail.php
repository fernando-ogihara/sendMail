<?php

include 'users.php';

function sendEmail(string $userRole, array $array, string $id) {
    // check if the user is the admin and has permission to send emails
    if ($userRole !== 'admin') {
        logReport('error_log', "user role error");
        return "Permission denied.";
    } 
    // validate if the id is a number
    if (!is_numeric($id)) {
        return "Invalid ID format.";
    }
    // log the attempt to send email
    logReport('email_sent_log', "Attempted to send email to ID: $id");
    // rate limiting
    rateLimit();
    // check if the user id exists in the array
    foreach ($array as $user) {
        if ($user['id'] == $id) {
            // validate if the email is valid
            validateEmail($user);
        }
    }
    // If the user with the given ID is not found
    return "User with ID not found.";
}
function logReport(string $log_file, string $message) {
    file_put_contents("../logs/" . $log_file . ".txt", date("Y-m-d H:i:s") . " - $message\n", FILE_APPEND);
}
function rateLimit() {
    session_start();
    if (!isset($_SESSION['last_email_time'])) {
        $_SESSION['last_email_time'] = time();
    } else {
        $diff = time() - $_SESSION['last_email_time'];
        if ($diff < 10) { // 10 seconds between sends
            return "Please wait before sending another email.";
        }
        $_SESSION['last_email_time'] = time();
    }
}
function validateEmail(array $user) {
    if (!filter_var($user['email'], FILTER_VALIDATE_EMAIL)) {
        return "Invalid email format.";
    } else {
        return $user['name'] . " => " . $user['email'];
    }
}

// test purposes
sendEmail('admin', $users, '1');
// error test
sendEmail('ogi', $users, '1');