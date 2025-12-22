<?php
include "includes/header.php";
require "includes/functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $name = formatName($_POST['name']);
        $email = $_POST['email'];
        $skills = cleanSkills($_POST['skills']);

        if (!$name || !validateEmail($email)) {
            throw new Exception("Invalid name or email.");
        }

        saveStudent($name, $email, $skills);
        $message = "Student saved successfully.";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<h2>Add Student</h2>
<p><?php echo $message; ?></p>

<form method="POST">
    Name: <input type="text" name="name" required><br><br>
    Email: <input type="email" name="email" required><br><br>
    Skills (comma-separated): <input type="text" name="skills"><br><br>
    <input type="submit" value="Save">
</form>

<?php include "includes/footer.php"; ?>
