<?php

$errors = [];
$isSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = verifyInput($_POST["name"]);
    $lname = verifyInput($_POST["lname"]);
    $email = verifyInput($_POST["email"]);
    $subject = verifyInput($_POST["subject"]);
    $message = verifyInput($_POST["message"]);

    if (empty($name)) {
        $errors["name"] = "Please enter your name.";
    }

    if (empty($lname)) {
        $errors["lname"] = "Please enter your last name.";
    }

    if (!isEmail($email)) {
        $errors["email"] = "Please enter a valid email address.";
    }

    if (empty($subject)) {
        $errors["subject"] = "Please enter a subject.";
    }

    if (empty($message)) {
        $errors["message"] = "Please enter a message.";
    }

    if (empty($errors)) {
        $isSuccess = true;
        $emailTo = "support@vizaahtech.com"; // Replace with your email address
        $emailSubject = "Contact Form Submission: $subject";
        $emailBody = "Name: $name $lname\n";
        $emailBody .= "Email: $email\n";
        $emailBody .= "Message: $message\n";

        $headers = "From: $name $lname <$email>\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";

        if (mail($emailTo, $emailSubject, $emailBody, $headers)) {
            $response = [
                "success" => true,
                "message" => "Thank you! Your message has been sent successfully."
            ];
        } else {
            $response = [
                "success" => false,
                "message" => "Sorry, an error occurred while sending your message. Please try again later."
            ];
        }

        echo json_encode($response);
        exit;
    } else {
        $response = [
            "success" => false,
            "errors" => $errors
        ];
        echo json_encode($response);
        exit;
    }
}

function isEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function verifyInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
