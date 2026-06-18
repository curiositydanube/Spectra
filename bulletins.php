<?php
require_once 'admin-core.php';

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
$channel_user = isset($_GET['user']) ? trim((string)$_GET['user']) : '';
$user_id = 0;
$post_status = "";
$channel_posts = [];

if ($channel_user !== '') {
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $channel_user);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    if ($res) { $user_id = (int)$res['id']; }
    $stmt->close();
}

// Handle Text + Hotlink Post Submission
if ($user_id > 0 && $_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit_bulletin'])) {
    if (!isset($_SESSION['user_id'])) {
        $post_status = "You must be logged in to post.";
    } else {
        $content = trim($_POST['content']);
        $hotlink = trim($_POST['hotlink_url']);
        
        if (!empty($content) || !empty($hotlink)) {
            $stmt = $conn->prepare("INSERT INTO community_posts (user_id, content, image_url) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $content, $hotlink);
            $stmt->execute();
            $stmt->close();
            header("Location: bulletins.php?user=" . urlencode($channel_user));
            exit();
        } else {
            $post_status = "Post content or image URL is required.";
        }
    }
}

// Fetch Posts
if ($user_id > 0) {
    $stmt = $conn->prepare("SELECT content, image_url, created_at FROM community_posts WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $channel_posts[] = $row; }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($channel_user); ?> - Bulletin Board</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div><h1>Spectra<span style="background-color: #0055FF;">Video</span></h1><i>Bulletin Board</i></div>
        <nav><ul><li><a href="index.php">Home</a></li><li><a href="channel/<?php echo urlencode($channel_user); ?>">Back to Channel</a></li></ul></nav>
    </header>

    <main style="max-width: 600px; margin: 20px auto;">
        <h2>Bulletin Board: <?php echo htmlspecialchars($channel_user); ?></h2>

        <div style="border: 1px solid #CCC; padding: 15px; background: #F9F9F9; margin-bottom: 20px;">
            <form action="" method="POST">
                <textarea name="content" placeholder="Share an update..." style="width:100%; height:60px;"></textarea>
                <div style="margin-top:10px;">
                    <input type="url" name="hotlink_url" placeholder="Paste image hotlink URL here..." style="width:100%;">
                </div>
                <button type="submit" name="submit_bulletin" style="margin-top:10px;">Post to Bulletin</button>
            </form>
        </div>

        <?php foreach ($channel_posts as $post): ?>
            <div style="border-bottom: 1px solid #EEE; padding: 10px 0;">
                <?php if (!empty($post['content'])): ?>
                    <p style="margin: 0;"><?php echo htmlspecialchars($post['content']); ?></p>
                <?php endif; ?>
                
                <?php if (!empty($post['image_url'])): ?>
                    <img src="<?php echo htmlspecialchars($post['image_url']); ?>" style="max-width:100%; border:1px solid #999; margin-top:10px;">
                <?php endif; ?>
                
                <small style="color:#666; display:block; margin-top:5px;"><?php echo $post['created_at']; ?></small>
            </div>
        <?php endforeach; ?>
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