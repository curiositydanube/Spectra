<?php
session_start();

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$subscribed_channels = array();

if (isset($_SESSION['subscribed_to']) && !empty($_SESSION['subscribed_to'])) {
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        // Collect array keys representing the subscriber user IDs
        $ids = array_keys($_SESSION['subscribed_to']);
        $id_list = implode(',', array_map('intval', $ids));
        
        $query = "SELECT username, profile_pic FROM users WHERE id IN ($id_list) ORDER BY username ASC";
        $result = $conn->query($query);
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $subscribed_channels[] = $row;
            }
        }
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectra Video - Subscribed Channels</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div>
            <div>
                <h1>Spectra<span style="background-color: #0055FF;">Video</span></h1>
                <i>Broadcast Yourself™</i>
            </div>
            <p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: bold;">
                        <img src="../images/<?php echo htmlspecialchars($_SESSION['profile_pic']); ?>" alt="Profile" style="width: 22px; height: 22px; border: 1px solid #999; object-fit: cover;">
                        Hello, <a href="channel/<?php echo htmlspecialchars($_SESSION['username']); ?>"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        | <a href="settings.php">Settings</a> | <a href="index.php?action=logout">Log Out</a>
                    </span>
                <?php else: ?>
                    <a href="register.php">Sign Up</a> | <a href="index.php">Log In</a>
                <?php endif; ?>
            </p>
        </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="most-recent.php">Videos</a></li>
                <li><a href="channels.php">Channels</a></li>
                <li><a href="copyright.php">Copyright</a></li>
                <li><a href="upload.php">Upload</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section style="width: 100%;">
            <h2>Subscribed Channels</h2>
            <?php if (empty($subscribed_channels)): ?>
                <p style="font-style: italic; color: #666;">You haven't subscribed to any creators yet!</p>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-top: 15px;">
                    <?php foreach ($subscribed_channels as $channel): ?>
                        <div style="border: 1px solid #CCC; background: #FAFAF9; padding: 10px; text-align: center;">
                            <img src="../images/<?php echo htmlspecialchars($channel['profile_pic']); ?>" style="width: 70px; height: 70px; object-fit: cover; border: 1px solid #999;"><br>
                            <a href="channel/<?php echo htmlspecialchars($channel['username']); ?>" style="font-weight: bold; font-size: 14px; text-decoration: none; display: inline-block; margin-top: 5px;">
                                <?php echo htmlspecialchars($channel['username']); ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
    <hr>
    <div style="font-size: 11px; text-align: center;">
        <a href="rules.php">Rules</a> | 
        <a href="privacypolicy.php">Privacy Policy</a>
    </div>
    <div style="margin-top: 5px; text-align: center;">
        <small>Spectra &copy; <?php echo date("Y"); ?> | <strong>Repository:</strong> v2006.06.18-BETA</small>
    </div>
</footer>

</body>
</html>