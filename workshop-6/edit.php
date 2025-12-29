<?php
require 'db.php';
$con = dbConnect();

$id = $_GET['id'];

$stmt = $con->prepare("SELECT * FROM student WHERE id = ?");
$stmt->execute([$id]);
$student = $stmt->fetch();

if (isset($_POST['update'])) {
    $sql = "UPDATE student SET name=?, age=? WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$_POST['name'], $_POST['age'], $id]);
    header("Location: index.php");
    exit;
}
?>

<form method="post">
    <input type="text" name="name" value="<?= $student['name'] ?>" required>
    <br>
    <input type="number" name="age" value="<?= $student['age'] ?>" required>
    <br>
    <button type="submit" name="update">Update</button>
</form>

