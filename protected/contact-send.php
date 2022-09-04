
<?php

/**
 * Honeypot for bots.
 */
if ($_POST["website"] !== "") {
    echo "exiting";
    exit;
}

$sender = htmlspecialchars($_POST["email"]);
$subject = htmlspecialchars($_POST["subject"]);
$message = htmlspecialchars($_POST["message"]);

$message = "From: $sender\n" . $message;

$message = wordwrap($message, 70, "\r\n");

$receiver = "oljo@protonmail.ch";

if (!filter_var($sender, FILTER_VALIDATE_EMAIL)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Please enter a valid email."];
    redirect("/contact");
}

if (!isset($subject)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Subject can't be empty."];
    redirect("/contact");
}

if (!isset($message)) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Message can't be empty."];
    redirect("/contact");
}

if (strlen($subject) > 100) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Subject can't be more than 100 characters long."];
    redirect("/contact");
}

if (strlen($message) > 2000) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Message can't be more than 2000 characters long."];
    redirect("/contact");
}

if (mail($receiver, $subject, $message)) {
    $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "Your message was sent."];
    redirect("/contact");
} else {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Could not send message."];
    redirect("/contact");
}
