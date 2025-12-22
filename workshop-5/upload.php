<?php
include "includes/header.php";
require "includes/functions.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $fileName = uploadPortfolioFile($_FILES['portfolio']);
        $message = "File uploaded successfully: $fileName";
    } catch (Exception $e) {
        $message = "Error: " . $e->getMessage();
    }
}
?>

<h2>Upload Portfolio</h2>
<p><?php echo $message; ?></p>

<form method="POST" enctype="multipart/form-data">
    Select file: <input type="file" name="portfolio" required><br><br>
    <input type="submit" value="Upload">
</form>

<?php include "includes/footer.php"; ?>
