<?php
session_start();
include "db.php"; // your database connection
$error = "";

if (isset($_POST['login'])) {
    $loginInput = trim($_POST['username']); // username or email
    $password = trim($_POST['password']);

    if (empty($loginInput) || empty($password)) {
        $error = "All fields are required";
    } else {
        // Fetch user by username OR email
        $stmt = $conn->prepare("SELECT username, role, password FROM users WHERE username=? OR email=?");
        $stmt->bind_param("ss", $loginInput, $loginInput);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($username, $role, $dbPassword);
            $stmt->fetch();

            // Verify password: either password_hash OR MD5 (for old admin)
            if (password_verify($password, $dbPassword) || md5($password) === $dbPassword) {
                // Set session variables
                $_SESSION['user'] = $username;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    header("Location: admin.php");
                } else {
                    header("Location: index.html");
                }
                exit;
            } else {
                $error = "Invalid username/email or password";
            }
        } else {
            $error = "Invalid username/email or password";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SymptomCheck</title>
    <style>
        /* Reset and basic styles */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Arial', sans-serif; }
        body { background: #f2f5f8; color: #333; }
        .container {
            max-width: 400px;
            margin: 80px auto;
            padding: 30px 25px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 { color: #4b0082; margin-bottom: 20px; font-size: 2em; }
        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            transition: all 0.3s ease;
        }
        form input:focus {
            outline: none;
            border-color: #4b0082;
            box-shadow: 0 0 5px rgba(75, 0, 130, 0.3);
        }
        button {
            width: 100%;
            padding: 14px;
            margin-top: 15px;
            border: none;
            border-radius: 8px;
            background: #4b0082;
            color: #fff;
            font-size: 1.1em;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        button:hover { background: #6a00b8; }
        .error { color: #ff0000; margin-bottom: 15px; }
        .link { margin-top: 15px; }
        .link a { color: #4b0082; text-decoration: none; font-weight: bold; }
        .link a:hover { text-decoration: underline; }
        @media (max-width: 500px) {
            .container { margin: 40px 15px; padding: 25px 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>

    <?php if ($error != ""): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form name="loginForm" method="post" onsubmit="return validateLogin()">
        <input type="text" name="username" placeholder="Username or Email" required>
        <input type="password" name="password" placeholder="Password" required>
        <button name="login">Login</button>
    </form>

    <div class="link">
        <a href="register.php">Create account</a>
    </div>
</div>

<script>
function validateLogin() {
    const username = document.loginForm.username.value.trim();
    const password = document.loginForm.password.value.trim();
    if (username === "" || password === "") {
        alert("Please fill in all fields.");
        return false;
    }
    return true;
}
</script>
</body>
</html>
