<?php
session_start();
include "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = trim($_POST['username_email']);
    $password = trim($_POST['password']);
    $role = $_POST['role'];

    $stmt = $conn->prepare("SELECT id, username, role FROM users WHERE (username=? OR email=?) AND password=? AND role=?");
    $stmt->bind_param("ssss", $username_email, $username_email, $password, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit();
    } else {
        $message = "Invalid credentials or role!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login - Timetable Scheduler</title>
<style>
/* Reset and body */
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* Login card */
.login-container {
    background: #ffffff;
    padding: 40px 50px;
    border-radius: 12px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.3);
    width: 380px;
    transition: transform 0.3s;
}
.login-container:hover {
    transform: translateY(-5px);
}

h2 {
    text-align: center;
    margin-bottom: 25px;
    color: #4a4a4a;
}

input[type=text], input[type=password] {
    width: 100%;
    padding: 12px 15px;
    margin: 10px 0 20px 0;
    border: 1px solid #ccc;
    border-radius: 8px;
    transition: all 0.3s;
}
input[type=text]:focus, input[type=password]:focus {
    border-color: #764ba2;
    box-shadow: 0 0 5px rgba(118,75,162,0.5);
    outline: none;
}

.roles {
    display: flex;
    justify-content: space-around;
    margin-bottom: 25px;
}
.roles label {
    font-weight: bold;
    color: #555;
}
.roles input[type=radio] {
    margin-right: 5px;
}

button {
    width: 100%;
    padding: 12px;
    background: #764ba2;
    color: #fff;
    font-size: 16px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s;
}
button:hover { background: #667eea; }

.message {
    color: #e74c3c;
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
}
</style>
</head>
<body>
<div class="login-container">
    <h2>Timetable Scheduler Login</h2>
    <?php if($message != "") { echo "<div class='message'>$message</div>"; } ?>
    <form method="post" action="">
        <input type="text" name="username_email" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <div class="roles">
            <label><input type="radio" name="role" value="admin" required> Admin</label>
            <label><input type="radio" name="role" value="faculty"> Faculty</label>
            <label><input type="radio" name="role" value="student"> Student</label>
        </div>
        <button type="submit">Login</button>
    </form>
</div>
</body>
</html>
