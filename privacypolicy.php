<?php require_once 'admin-core.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Spectra - Privacy Policy</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div>
            <div><h1>Spectra<span style="background-color: #0055FF;">Video</span></h1><i>Broadcast Yourself™</i></div>
            <p>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: bold;">
                        <img src="images/<?php echo htmlspecialchars((string)$_SESSION['profile_pic']); ?>" style="width: 22px; height: 22px; border: 1px solid #999;">
                        Hello, <a href="channel/<?php echo htmlspecialchars($_SESSION['username']); ?>"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                        | <a href="settings.php">Settings</a> | <a href="index.php?action=logout">Log Out</a>
                    </span>
                <?php else: ?>
                    <a href="register.php">Sign Up</a> | <a href="index.php">Log In</a> | <a href="#">Help</a>
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

    <main style="padding: 15px;">
        <h2>Privacy Policy</h2>
        <p>Effective Date: June 18, 2026</p>
        <h3>Data Collection</h3>
        <p>We collect basic account information provided during registration and log IP addresses for moderation.</p>
        <h3>Data Usage</h3>
        <p>Your data is used solely to maintain your account, facilitate video streaming, and display community content. We do not sell your personal data.</p>
    </main>

    <footer>
        <hr>
        <div style="font-size: 11px; text-align: center;">
            <a href="rules.php">Rules</a> | <a href="privacypolicy.php">Privacy Policy</a>
        </div>
        <div style="margin-top: 5px; text-align: center;">
            <small>Spectra &copy; <?php echo date("Y"); ?> | <strong>Repository:</strong> v2006.06.18-BETA</small>
        </div>
    </footer>
</body>
</html>