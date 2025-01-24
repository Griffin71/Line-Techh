<?php   
    require("./mailing/mailfunction.php");

    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $body = "<ul><li>Name: ".$name."</li><li>Phone: ".$phone."</li><li>Email: ".$email."</li><li>Message: ".$message."</li></ul>";

   // Define recipient email address
$recipientEmail = "kabelosamkelo19@gmail.com"; // Replace with the actual recipient email

// Prepare the subject for the email
$subject = "New Contact Form Submission";

// Send email using the mailfunction
$status = mailfunction($recipientEmail, $subject, $body); // Send email to the recipient

// Check if the email was sent successfully
if ($status) {
    echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
} else {
    echo '<center><h1>Error sending message! Please try again.</h1></center>';
}

?>
