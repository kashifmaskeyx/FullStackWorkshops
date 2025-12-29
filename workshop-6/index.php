<?php
require 'db.php';
$con = dbConnect();

$sql = "SELECT * FROM student";
$data = $con->query($sql)->fetchAll();
?>

<table border="1">
    <tr>
        <th>Name</th>
        <th>Age</th>
        <th>Actions</th>
    </tr>

    <?php foreach ($data as $student): ?>
        <tr>
            <td><?= $student['name'] ?></td>
            <td><?= $student['age'] ?></td>
            <td>
                <a href="edit.php?id=<?= $student['id'] ?>">Edit</a> |
                <a href="delete.php?id=<?= $student['id'] ?>">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<br>

<form method="POST" action="insert.php">
    <input type="text" name="name" placeholder="Enter student name" required>
    <br>
    <input type="number" name="age" placeholder="Enter student age" required>
    <br>
    <input type="submit" value="Add Student">
</form>
