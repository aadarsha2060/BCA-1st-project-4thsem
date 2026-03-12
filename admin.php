<?php
session_start();
require_once "db.php";

// Check if user is logged in and is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Handle form submissions for adding disease or symptom
$success = $error = "";

if (isset($_POST['add_disease'])) {
    $name = trim($_POST['disease_name']);
    $desc = trim($_POST['description']);
    $severity = $_POST['severity'];

    if ($name && $desc) {
        $stmt = $conn->prepare("INSERT INTO diseases (name, description, severity) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $desc, $severity);
        if ($stmt->execute()) {
            $success = "Disease added successfully!";
        } else {
            $error = "Error adding disease.";
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields.";
    }
}

if (isset($_POST['add_symptom'])) {
    $disease_id = $_POST['disease_id'];
    $symptom = trim($_POST['symptom']);

    if ($disease_id && $symptom) {
        $stmt = $conn->prepare("INSERT INTO symptoms (disease_id, symptom) VALUES (?, ?)");
        $stmt->bind_param("is", $disease_id, $symptom);
        if ($stmt->execute()) {
            $success = "Symptom added successfully!";
        } else {
            $error = "Error adding symptom.";
        }
        $stmt->close();
    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f2f5f8; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 30px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        h2 { color: #4b0082; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #4b0082; color: #fff; }
        input, select, button { padding: 8px; margin: 5px 0; border-radius: 4px; border: 1px solid #ccc; }
        button { background: #4b0082; color: #fff; cursor: pointer; }
        button:hover { background: #6a00b8; }
        .success { color: green; }
        .error { color: red; }
        .section { margin-bottom: 40px; }
        a { text-decoration: none; color: #4b0082; font-weight: bold; }
    </style>
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! | <a href="logout.php">Logout</a></p>

    <?php if($success) echo "<p class='success'>$success</p>"; ?>
    <?php if($error) echo "<p class='error'>$error</p>"; ?>

    <!-- Users Section -->
    <div class="section">
        <h3>All Users</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created At</th>
            </tr>
            <?php
            $res = $conn->query("SELECT * FROM users");
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>".htmlspecialchars($row['username'])."</td>
                        <td>".htmlspecialchars($row['email'])."</td>
                        <td>{$row['role']}</td>
                        <td>{$row['created_at']}</td>
                    </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Diseases Section -->
    <div class="section">
        <h3>Add Disease</h3>
        <form method="post">
            <input type="text" name="disease_name" placeholder="Disease Name" required>
            <input type="text" name="description" placeholder="Description" required>
            <select name="severity">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <button type="submit" name="add_disease">Add Disease</button>
        </form>

        <h4>All Diseases</h4>
        <table>
            <tr><th>ID</th><th>Name</th><th>Description</th><th>Severity</th></tr>
            <?php
            $res = $conn->query("SELECT * FROM diseases");
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>".htmlspecialchars($row['name'])."</td>
                        <td>".htmlspecialchars($row['description'])."</td>
                        <td>{$row['severity']}</td>
                    </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Symptoms Section -->
    <div class="section">
        <h3>Add Symptom</h3>
        <form method="post">
            <select name="disease_id" required>
                <option value="">Select Disease</option>
                <?php
                $res = $conn->query("SELECT id, name FROM diseases");
                while($row = $res->fetch_assoc()) {
                    echo "<option value='{$row['id']}'>".htmlspecialchars($row['name'])."</option>";
                }
                ?>
            </select>
            <input type="text" name="symptom" placeholder="Symptom" required>
            <button type="submit" name="add_symptom">Add Symptom</button>
        </form>

        <h4>All Symptoms</h4>
        <table>
            <tr><th>ID</th><th>Disease</th><th>Symptom</th></tr>
            <?php
            $res = $conn->query("SELECT s.id, d.name AS disease, s.symptom FROM symptoms s JOIN diseases d ON s.disease_id = d.id");
            while($row = $res->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>".htmlspecialchars($row['disease'])."</td>
                        <td>".htmlspecialchars($row['symptom'])."</td>
                    </tr>";
            }
            ?>
        </table>
    </div>
</div>
</body>
</html>
