<?php
require 'db.php';
$con = dbConnect();

$id = $_GET['id'];

$sql = "DELETE FROM student WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->execute([$id]);

header("Location: index.php");
exit;
?>
