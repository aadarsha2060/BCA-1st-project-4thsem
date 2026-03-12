<?php
session_start();
require_once "db.php"; // Make sure this connects to your database

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Use isset() to avoid undefined array key warnings
    $username = isset($_POST['username']) ? trim($_POST['username']) : "";
    $email    = isset($_POST['email']) ? trim($_POST['email']) : "";
    $password = isset($_POST['password']) ? trim($_POST['password']) : "";
    $cpassword= isset($_POST['cpassword']) ? trim($_POST['cpassword']) : "";

    // Basic validation
    if (empty($username) || empty($email) || empty($password) || empty($cpassword)) {
        $message = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
    } elseif ($password !== $cpassword) {
        $message = "Passwords do not match.";
    } else {

        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username already exists.";
            $stmt->close();
        } else {
            $stmt->close();

            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $message = "Email already registered.";
                $stmt->close();
            } else {
                $stmt->close();

                // Hash the password
                $hash = password_hash($password, PASSWORD_DEFAULT);

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, 'user')");
                $stmt->bind_param("sss", $username, $email, $hash);

                if ($stmt->execute()) {
                    $stmt->close();
                    // Registration successful, redirect to login
                    header("Location: login.php");
                    exit();
                } else {
                    $message = "Registration failed: " . $conn->error;
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <script src="validation.js" defer></script>
</head>
<body>

<form class="login-box" method="post" onsubmit="return validateRegister()">
    <h2>Register</h2>

    <input type="text" id="rusername" name="username" placeholder="Username" required>
    <input type="email" id="remail" name="email" placeholder="Email" required>
    <input type="password" id="rpassword" name="password" placeholder="Password" required>
    <input type="password" id="rcpassword" name="cpassword" placeholder="Confirm Password" required>

    <button type="submit">Register</button>

    <?php if ($message !== ""): ?>
        <p class="error"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <p class="info">Already have an account? <a href="login.php">Login</a></p>
</form>

</body>
</html>
