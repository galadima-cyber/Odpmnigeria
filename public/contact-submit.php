<?php
require_once dirname(__DIR__) . '/includes/db.php';
require_once dirname(__DIR__) . '/includes/functions.php';

global $config;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $interest = trim($_POST['interest'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    // Validate
    if (empty($fname) || empty($lname) || empty($email) || empty($interest) || empty($message)) {
        header('Location: /ODPM/public/index.php?error=missing_fields');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: /ODPM/public/index.php?error=invalid_email');
        exit;
    }
    
    // Insert into database
    try {
        $stmt = db()->prepare('INSERT INTO contact_submissions (first_name, last_name, email, interest, message) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$fname, $lname, $email, $interest, $message]);
        
        // Send email notification to admin
        $adminEmail = $config['email']['admin_email'];
        $subject = 'New Get Involved Submission - ODPM Nigeria';
        
        $emailBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: #118B50; color: white; padding: 20px; text-align: center; }
                .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; }
                .field { margin-bottom: 15px; }
                .label { font-weight: bold; color: #118B50; }
                .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>New Get Involved Submission</h2>
                </div>
                <div class='content'>
                    <div class='field'>
                        <span class='label'>Name:</span> {$fname} {$lname}
                    </div>
                    <div class='field'>
                        <span class='label'>Email:</span> {$email}
                    </div>
                    <div class='field'>
                        <span class='label'>Interest:</span> " . ucfirst($interest) . "
                    </div>
                    <div class='field'>
                        <span class='label'>Message:</span><br>
                        " . nl2br(htmlspecialchars($message)) . "
                    </div>
                </div>
                <div class='footer'>
                    <p>View and respond to this submission in your admin dashboard.</p>
                    <p><a href='http://localhost/ODPM/admin/manage-contacts.php'>Go to Admin Dashboard</a></p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        send_email($adminEmail, $subject, $emailBody);
        
        header('Location: /ODPM/public/index.php?success=contact_sent');
        exit;
        
    } catch (Exception $e) {
        header('Location: /ODPM/public/index.php?error=submission_failed');
        exit;
    }
} else {
    header('Location: /ODPM/public/index.php');
    exit;
}
