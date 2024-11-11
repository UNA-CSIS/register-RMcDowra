<?php
session_start();

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
} else {
    header("location: index.php");
    exit;
}

// Database connection
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "softball";

// Connect to database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch games
$sql = "SELECT opponent, site, result FROM games";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <h3>Games</h3>
    
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1' cellpadding='10'>";
        echo "<tr><th>Opponent</th><th>Site</th><th>Result</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row["opponent"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["site"]) . "</td>";
            echo "<td>" . htmlspecialchars($row["result"]) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No games found.";
    }

    // Close the database connection
    $conn->close();
    ?>
</body>
</html>
