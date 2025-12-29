<?php
 require 'db.php';
 $con = dbConnect();

 $name = $_POST['name'];
 $age = $_POST['age'];

 $con ->exec("CREATE TABLE IF NOT EXISTS student(name varchar(10), age int)");

 $sql = "INSERT INTO student VALUES (?, ?)";
 $statement = $con->prepare($sql);
 $statement -> bindValue(1, $name);
 $statement -> bindValue(2, $age);
 $statement -> execute();
 echo "Data inserted into table";
 header("Location: index.php");
?>