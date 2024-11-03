<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

session_start(); 

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['loginBtn'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (!empty($username) && !empty($password)) {
        $user = loginUser($pdo, $username, $password); 

        if ($user) {
            $_SESSION['user_id'] = $user['User_ID']; 
            header('Location: index.php'); 
            exit();
        } else {
            $errorMessage = "Invalid username or password."; 
        }
    } else {
        $errorMessage = "Please fill in both fields."; 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Shop Login</title>
    <link rel="stylesheet" href="styles.css"> 
</head>
<body>
    <div class="login-container">
        <h1>Welcome to Foodtripan ni Owjey</h1>
        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" name="loginBtn">Login</button>
        </form>
        
        <div class="register-container">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>