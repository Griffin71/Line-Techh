<?php
require 'vendor/autoload.php'; // Load PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Define the uploads directory
$uploadsDir = __DIR__ . "/tmp-uploads/";

// Ensure the uploads directory exists
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0777, true);
}

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and retrieve form data
    $name = htmlspecialchars(trim($_POST['name']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $email = htmlspecialchars(trim($_POST['email']));
    $applyfor = htmlspecialchars(trim($_POST['status']));
    $experience = htmlspecialchars(trim($_POST['experience']));
    $otherdetails = htmlspecialchars(trim($_POST['details']));

    // Validate form data
    if (empty($name) || empty($phone) || empty($email)) {
        die("All required fields (name, phone, email) must be filled.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    if (!preg_match('/^[0-9]{10,14}$/', $phone)) {
        die("Invalid phone number. Ensure it is between 10 and 14 digits.");
    }

    // Handle file upload
    if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] === UPLOAD_ERR_OK) {
        $filename = $name . ".pdf"; // Name the file after the user
        $fileTmp = $_FILES["fileToUpload"]["tmp_name"];
        $fileDestination = $uploadsDir . $filename;

        // Move uploaded file to the uploads directory
        if (!move_uploaded_file($fileTmp, $fileDestination)) {
            die("Error uploading file. Please try again.");
        }
    } else {
        die("No file uploaded or an error occurred during the upload process.");
    }

    // Construct email content
    $body = "<ul>
                <li>Name: $name</li>
                <li>Phone: $phone</li>
                <li>Email: $email</li>
                <li>Applying For: $applyfor</li>
                <li>Experience: $experience years</li>
                <li>Details: $otherdetails</li>
            </ul>";

    // Send the email with PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'pro.turbo-smtp.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'kabelosamkelo19@gmail.com'; // Replace with your email
        $mail->Password = 'Kgosana1771!'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 465;

        // Recipients
        $mail->setFrom('kabelosamkelo19@gmail.com', 'Your Name'); // Replace sender email/name
        $mail->addAddress('kabelosamkelo19@gmail.com'); // Replace recipient email

        // Attachments
        $mail->addAttachment($fileDestination, $filename);

        // Content
        $mail->isHTML(true);
        $mail->Subject = "New Application Submission from $name";
        $mail->Body = $body;

        $mail->send();
        echo '<center><h1>Thank you! Your application has been submitted successfully.</h1></center>';
    } catch (Exception $e) {
        echo '<center><h1>Error sending email: ' . $mail->ErrorInfo . '</h1></center>';
    }
} else {
    echo '<center><h1>Invalid request. Please submit the form properly.</h1></center>';
}
?>
