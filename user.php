<?php
session_start();
require_once "db.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>User Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="login-box" style="width:600px;">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h2>
    <a href="logout.php">Logout</a>

    <hr>

    <h3>Symptom Checker</h3>

    <form method="POST">
        <input type="text" name="symptoms" placeholder="Example: fever, cough" required>
        <button type="submit" name="analyze">Analyze</button>
    </form>

<?php
if (isset($_POST['analyze'])) {

    $input = strtolower(trim($_POST['symptoms']));
    $userSymptoms = array_unique(array_map('trim', explode(',', $input)));

    if (count($userSymptoms) < 2) {
        echo "<p style='color:red;'>Enter at least 2 symptoms.</p>";
    } else {

        $diseases = mysqli_query($conn, "SELECT * FROM diseases");
        $found = false;

        while ($d = mysqli_fetch_assoc($diseases)) {

            $stmt = $conn->prepare("SELECT symptom FROM symptoms WHERE disease_id=?");
            $stmt->bind_param("i", $d['id']);
            $stmt->execute();
            $symptomsResult = $stmt->get_result();

            $matches = [];

            while ($s = $symptomsResult->fetch_assoc()) {
                if (in_array(strtolower($s['symptom']), $userSymptoms)) {
                    $matches[] = $s['symptom'];
                }
            }

            $uniqueMatches = array_unique($matches);

            if (count($uniqueMatches) >= 2) {
                $found = true;
                echo "<div style='margin-top:15px;padding:10px;border:1px solid #ccc;'>";
                echo "<strong>" . htmlspecialchars($d['name']) . "</strong><br>";
                echo "<small>Severity: " . ucfirst($d['severity']) . "</small>";
                echo "<p>" . htmlspecialchars($d['description']) . "</p>";
                echo "<b>Matched:</b> " . implode(", ", $uniqueMatches);
                echo "</div>";
            }
        }

        if (!$found) {
            echo "<p style='color:orange;'>No matching diseases found.</p>";
        }
    }
}
?>
</div>

</body>
</html>
