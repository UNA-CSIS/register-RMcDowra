 <?php
session_start();

// Database connection credentials
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "softball";

// Helper function to scrub/validate form inputs
function validate_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Get and scrub form data
$username = validate_input($_POST['user']);
$password = validate_input($_POST['pwd']);
$confirm_password = validate_input($_POST['repeat']);

// Check if password values match
if ($password !== $confirm_password) {
    die("Passwords do not match!");
}

// Hash the password
$password_hash = password_hash($password, PASSWORD_DEFAULT);

try {
    // Login to the database
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if the user already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        die("Username already exists. Please choose another.");
    }

    // Insert new user into the database
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password_hash);

    if ($stmt->execute()) {
        // Store the username in session and redirect, or prompt login
        $_SESSION['username'] = $username;
        echo "Registration successful. Welcome, $username!";
    } else {
        echo "Error: Registration failed.";
    }

} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage();
}

?>
