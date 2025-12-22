<?php

function formatName($name) {
    return ucwords(strtolower(trim($name)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanSkills($string) {
    $skills = explode(",", $string);
    return array_map("trim", $skills);
}

function saveStudent($name, $email, $skillsArray) {
    $line = $name . "|" . $email . "|" . implode(",", $skillsArray) . PHP_EOL;
    file_put_contents("students.txt", $line, FILE_APPEND);
}

function uploadPortfolioFile($file) {
    if ($file['error'] !== 0) {
        throw new Exception("File upload error.");
    }

    $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024;

    $fileSize = $file['size'];
    $fileExt = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedTypes)) {
        throw new Exception("Invalid file type.");
    }

    if ($fileSize > $maxSize) {
        throw new Exception("File size exceeds 2MB.");
    }

    if (!is_dir("uploads")) {
        throw new Exception("Uploads directory not found.");
    }

    $newName = uniqid("portfolio_") . "." . $fileExt;
    move_uploaded_file($file['tmp_name'], "uploads/" . $newName);

    return $newName;
}
