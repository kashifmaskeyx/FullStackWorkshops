<?php
$name = $email = $password = "";
$nameErr = $emailErr = $passwordErr = $confirmPasswordErr = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = trim($_POST["name"]);
        if (strlen($name) < 2) {
            $nameErr = "Name must be at least 2 characters";
        }
    }

    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = trim($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"];
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters";
        } elseif (!preg_match("/[a-zA-Z]/", $password)) {
            $passwordErr = "Password must contain at least one letter";
        } elseif (!preg_match("/[0-9]/", $password)) {
            $passwordErr = "Password must contain at least one number";
        } elseif (!preg_match("/[!@#$%^&*(),.?\":{}|<>]/", $password)) {
            $passwordErr = "Password must contain at least one special character";
        }
    }

    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirm_password = $_POST["confirm_password"];
        if ($password !== $confirm_password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    if (empty($nameErr) && empty($emailErr) && empty($passwordErr) && empty($confirmPasswordErr)) {

        $filename = "users.json";

        if (!file_exists($filename)) {
            if (file_put_contents($filename, json_encode([])) === false) {
                die("File creation failed");
            }
        }

        $json_data = file_get_contents($filename);
        if ($json_data === false) {
            die("File read failed");
        }

        $users = json_decode($json_data, true);
        if ($users === null && json_last_error() !== JSON_ERROR_NONE) {
            die("JSON decode error");
        }

        foreach ($users as $user) {
            if ($user["email"] === $email) {
                $emailErr = "Email already registered";
                break;
            }
        }

        if (empty($emailErr)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $new_user = [
                "name" => $name,
                "email" => $email,
                "password" => $hashed_password,
                "registered_at" => date("Y-m-d H:i:s")
            ];

            $users[] = $new_user;

            if (file_put_contents($filename, json_encode($users, JSON_PRETTY_PRINT)) === false) {
                die("File write failed");
            }

            $success = true;
            $name = $email = "";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .error {
            color: red;
            font-size: 13px;
        }
        .success {
            color: green;
            background: #e8f5e9;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid green;
        }
        input[type="submit"] {
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 15px;
        }
        input[type="submit"]:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <h2>User Registration</h2>

    <?php if ($success): ?>
        <div class="success">Registration successful!</div>
    <?php endif; ?>

    <form method="POST" action="">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        <span class="error"><?php echo $nameErr; ?></span>

        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
        <span class="error"><?php echo $emailErr; ?></span>

        <label>Password:</label>
        <input type="password" name="password">
        <span class="error"><?php echo $passwordErr; ?></span>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password">
        <span class="error"><?php echo $confirmPasswordErr; ?></span>

        <input type="submit" value="Register">
    </form>
</body>
</html>
