<?php
require_once 'admin-core.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$status_message = "";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectra Video - Upload Video</title>
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
                <span style="display: inline-flex; align-items: center; gap: 6px; font-weight: bold;">
                    <img src="../images/<?php echo htmlspecialchars((string)$_SESSION['profile_pic']); ?>" alt="Profile" style="width: 22px; height: 22px; border: 1px solid #999; object-fit: cover;">
                    Hello, <a href="channel/<?php echo htmlspecialchars($_SESSION['username']); ?>"><?php echo htmlspecialchars($_SESSION['username']); ?></a>
                    | <a href="settings.php">Settings</a> | <a href="index.php?action=logout">Log Out</a>
                </span>
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
            <h2>Upload Video Metadata</h2>

            <div style="margin: 15px 0; padding: 12px; background: #FFF0F0; border: 1px solid #FFCACA; color: #CC0000; font-family: Arial, sans-serif;">
                <strong style="font-size: 14px; display: block; margin-bottom: 4px;">BROADCAST UPLOADS DISABLED</strong>
                <p style="margin: 0; font-size: 12px; line-height: 1.4;">
                    I can't figure out how to get video urls working, as so you will not be able to publish any new video's at this time for an indefinite period.
                </p>
            </div>

            <div title="form-container" style="max-width: 600px; opacity: 0.6;">
                <h3>Video Details (Read-Only)</h3>
                <form id="upload-form" onsubmit="return false;">
                    <p>
                        <label>Video Title *</label>
                        <input type="text" name="title" disabled style="width: 300px; background: #F0F0F0; cursor: not-allowed;">
                    </p>
                    <p>
                        <label>Video File URL *</label>
                        <input type="url" name="video_url" placeholder="https://example.com/movie.mp4" disabled style="width: 300px; background: #F0F0F0; cursor: not-allowed;">
                    </p>
                    <p>
                        <label style="vertical-align: top;">Description</label>
                        <textarea name="description" rows="4" disabled style="width: 300px; font-family: Arial; resize: none; background: #F0F0F0; cursor: not-allowed;"></textarea>
                    </p>
                    <p style="padding-left: 135px; margin-top: 15px;">
                        <span style="font-size: 14px; font-weight: bold; color: #888888; text-decoration: none; cursor: not-allowed;">
                            [ Publish Video Broadcast Disabled ]
                        </span>
                    </p>
                </form>
            </div>
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