<?php

if (!user_is_logged_in()) {
    echo json_encode([
        "message" => "You have to be logged in to do that."
    ]);
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
        echo json_encode([
            "message" => "File is not an image."
        ]);
        $uploadOk = 0;
        http_response_code(400);
        exit;
    }
} else {
    echo json_encode([
        "message" => "No file was posted."
    ]);
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

$target_file = str_replace([".jpg", ".jpeg", ".png"], ".webp", $target_file);

// Check if file already exists
if (file_exists($target_file)) {
    echo json_encode([
        "message" => "Sorry, file already exists."
    ]);
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

// Check file size
if ($_FILES["imageFile"]["size"] > 500000) {
    echo json_encode([
        "message" => "Sorry, your file is too large."
    ]);
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

// Allow certain file formats
if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "webp") {
    echo json_encode([
        "message" => "Sorry, only JPG, JPEG, PNG & WEBP files are allowed."
    ]);
    $uploadOk = 0;
    http_response_code(400);
    exit;
}

if ($uploadOk == 1) {
    try {
        move_uploaded_file($_FILES["imageFile"]["tmp_name"], $target_file);
        $info = getimagesize($target_file);

        $isAlpha = false;
        if ($info['mime'] == 'image/jpeg')
            $image = imagecreatefromjpeg($target_file);
        elseif ($isAlpha = $info['mime'] == 'image/gif') {
            $image = imagecreatefromgif($target_file);
        } elseif ($isAlpha = $info['mime'] == 'image/png') {
            $image = imagecreatefrompng($target_file);
        } elseif ($isAlpha = $info['mime'] == 'image/webp') {
            $image = imagecreatefrompng($target_file);
        } else {
            echo json_encode([
                "message" => "Sorry, there was an error uploading your file."
            ]);
            http_response_code(500);
            exit;
        }
        if ($isAlpha) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

        imagewebp($image, $target_file, 10);
        imagedestroy($image);

        echo json_encode([
            "message" => "The file has been uploaded.",
            "fileName" => basename($target_file)
        ]);
        http_response_code(201);
        exit;
    } catch (\Exception $e) {
        echo  json_encode([
            "message" => "There was an error uploading your file."
        ]);
        http_response_code(500);
        exit;
    }
}
