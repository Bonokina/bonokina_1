<?php
session_start();
if (!isset($_SESSION['username'])) {

    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $username = $_SESSION['username'];


    $conn = new mysqli("localhost", "root", "", "advertisements");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];

        $stmt = $conn->prepare("INSERT INTO ads (user_id, title, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $user_id, $title, $description);
        if ($stmt->execute()) {

            header("Location: ads.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "User not found";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Advertisement</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <h2>Add Advertisement</h2>
    <form id="addAdForm" action="add_ad.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea>
        <button type="submit">Add Advertisement</button>
    </form>
</body>

</html>