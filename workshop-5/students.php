<?php
include "includes/header.php";

if (file_exists("students.txt")) {
    $lines = file("students.txt");

    foreach ($lines as $line) {
        list($name, $email, $skills) = explode("|", trim($line));
        $skillsArray = explode(",", $skills);

        echo "<h3>$name</h3>";
        echo "<p>Email: $email</p>";
        echo "<p>Skills:</p><ul>";

        foreach ($skillsArray as $skill) {
            echo "<li>$skill</li>";
        }

        echo "</ul>";
    }
} else {
    echo "<p>No students found.</p>";
}

include "includes/footer.php";
?>
