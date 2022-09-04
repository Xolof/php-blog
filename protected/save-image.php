<?php

if (!user_is_logged_in()) {
    echo "You have to be logged in!";
    http_response_code(401);
    exit;
}

// Check if image file is a actual image or fake image
if (isset($_POST["saveImage"]) && isset($_FILES["imageFile"])) {
    $target_dir = dirname(__DIR__) . "/public/img/gallery/";
    $target_file = $target_dir . basename($_FILES["imageFile"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["imageFile"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
        http_response_code(400);
        exit;
    }
} else {
    echo "No file was posted.";
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

// Check file size
if ($_FILES["imageFile"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "webp") {
    echo "Sorry, only JPG, JPEG, PNG & WEBP files are allowed.";
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["imageFile"]["name"])) . " has been uploaded.";
        http_response_code(201);
        exit;
    } else {
        echo "Sorry, there was an error uploading your file.";
        http_response_code(500);
        exit;
    }
}
