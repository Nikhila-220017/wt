<?php
session_start();
if(!isset($_SESSION['user_name'])){
    header("Location: login.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            text-align: center;
            width: 400px;
        }
        h2 { color: #333; }
        p { color: #666; }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background: #f44336;
            color: white;
            border-radius: 5px;
            text-decoration: none;
        }
        a:hover { background: #d32f2f; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
        <p>You are successfully logged in.</p>
        <p>Email: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
        <a href="../backend/logout.php">Logout</a>
    </div>
</body>
</html>