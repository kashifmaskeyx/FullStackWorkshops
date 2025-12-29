<?php
function dbConnect() {
    $server = "mysql:host=localhost;dbname=NP03CS4A240161";
    $user = "NP03CS4A240161";
    $password = "N5mBDdBHcY";

    try {
        $con = new PDO($server, $user, $password);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $con->exec("
            CREATE TABLE IF NOT EXISTS student (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                age INT
            )
        ");
    } catch (PDOException $e) {
        die("Database connection error: " . $e->getMessage());
    }

    return $con;
}
?>

