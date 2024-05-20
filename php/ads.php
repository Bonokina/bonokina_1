<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$conn = new mysqli("localhost", "root", "", "advertisements");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$stmt = $conn->prepare("SELECT ads.title, ads.description, ads.created_at, users.username FROM ads JOIN users ON ads.user_id = users.id ORDER BY ads.created_at DESC");
$stmt->execute();
$result = $stmt->get_result();

$ads = [];
while ($row = $result->fetch_assoc()) {
    $ads[] = $row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Advertisements</title>
    <link rel="stylesheet" href="../styles.css">
</head>

<body>
    <h2>Advertisements</h2>


    <button onclick="location.href='add_ad.php'">Add Advertisement</button>

    <form action="logout.php" method="post">
        <button type="submit">Logout</button>
    </form>

    <?php if (count($ads) > 0) : ?>
        <ul>
            <?php foreach ($ads as $ad) : ?>
                <li>
                    <h3><?php echo htmlspecialchars($ad['title']); ?></h3>
                    <p><?php echo htmlspecialchars($ad['description']); ?></p>
                    <p><small>Posted by: <?php echo htmlspecialchars($ad['username']); ?> on <?php echo $ad['created_at']; ?></small></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <p>No advertisements available.</p>
    <?php endif; ?>
</body>

</html>