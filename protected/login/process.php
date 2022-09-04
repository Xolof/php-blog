<?php

if (user_is_logged_in()) {
    $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "You are already logged in"];
    redirect("/login");
    exit;
}

if (isset($_POST["action"]) && isset($_POST["username"]) && isset($_POST["password"])) {
    $postUsername = $_POST["username"];
    $postPassword= $_POST["password"];
    $userArr = getUsers();

    $errors = [];
    $user = false;
    $userId = false;

    foreach ($userArr as $item) {
        $username = $item->username;
        $passwordHash = $item->password;
        if ($username == $postUsername) {
            $user = $username;
            $id = $item->id;
            if (!password_verify($postPassword, $passwordHash)) {
                $errors[] = "Invalid password";
            }
        }
    }

    if (!$user) {
        $errors[] = "No user with that name";
    }

    if (count($errors) > 0) {
        $_SESSION["flash_message"] = ["cssClass" => "error", "message" => "Login failed"];
        redirect("/login");
    } else {
        $_SESSION["flash_message"] = ["cssClass" => "success", "message" => "You have been logged in"];
        // prevent session fixation attack
        session_regenerate_id();

        $_SESSION["username"] = $user;
        $_SESSION["user_id"]  = $id;
        redirect("/");
        exit;
    }
} else {
    redirect("/");
    exit;
};
