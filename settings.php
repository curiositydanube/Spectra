<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$status_message = "";
$current_user_id = (int)$_SESSION['user_id'];

$conn = new mysqli($host, $db_user, $db_pass, $db_name);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_desc = "";
$stmt = $conn->prepare("SELECT username, profile_pic, description FROM users WHERE id = ?");
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
if ($user_data = $result->fetch_assoc()) {
    $user_desc = isset($user_data['description']) ? $user_data['description'] : "";
}
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_settings'])) {
    $new_username = trim((string)$_POST['username']);
    $new_profile_pic = isset($_POST['profile_pic']) ? trim((string)$_POST['profile_pic']) : $_SESSION['profile_pic'];
    $new_description = isset($_POST['description']) ? trim((string)$_POST['description']) : "";

    if ($new_username === "") {
        $status_message = "Username cannot be empty.";
    } else {
        $update_stmt = $conn->prepare("UPDATE users SET username = ?, profile_pic = ?, description = ? WHERE id = ?");
        $update_stmt->bind_param("sssi", $new_username, $new_profile_pic, $new_description, $current_user_id);
        
        if ($update_stmt->execute()) {
            $_SESSION['username'] = $new_username;
            $_SESSION['profile_pic'] = $new_profile_pic;
            $user_desc = $new_description;

            setcookie("remember_user", $new_username, time() + (86400 * 30), "/");
            $status_message = "Settings updated successfully!";
        } else {
            $status_message = "Error saving profiles: " . $update_stmt->error;
        }
        $update_stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_account'])) {
    $delete_stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $delete_stmt->bind_param("i", $current_user_id);
    
    if ($delete_stmt->execute()) {
        $delete_stmt->close();
        $conn->close();

        session_destroy();
        setcookie("remember_user", "", time() - 3600, "/");
        
        header("Location: index.php?status=success");
        exit();
    } else {
        $status_message = "Failed to remove database profile: " . $delete_stmt->error;
        $delete_stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectra Video - Account Settings</title>
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
                    Hello, 
                    <a href="channel/<?php echo htmlspecialchars((string)$_SESSION['username']); ?>" style="text-decoration: none; color: #0033CC; font-weight: bold;">
                        <?php echo htmlspecialchars((string)$_SESSION['username']); ?>
                    </a>
                    | <a href="settings.php">Settings</a>
                    | <a href="index.php?action=logout">Log Out</a>
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
        <section style="width: 580px; padding: 15px;">
            <h2>Account Settings</h2>

            <?php if (!empty($status_message)): ?>
                <div title="status-message" style="margin: 15px 0; padding: 8px; background: #FFF9D7; border: 1px solid #E2C822; font-weight: bold; color: #333;">
                    <?php echo htmlspecialchars($status_message); ?>
                </div>
            <?php endif; ?>

            <div title="form-container" style="border: 1px solid #CCC; padding: 15px; background: #FFF;">
                <form action="settings.php" method="POST">
                    <input type="hidden" name="update_settings" value="1">
                    
                    <p style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Change Username:</label>
                        <input type="text" name="username" value="<?php echo htmlspecialchars((string)$_SESSION['username']); ?>" required style="width: 250px; padding: 3px;">
                    </p>

                    <p style="margin-bottom: 15px;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Channel Description / Bio:</label>
                        <textarea name="description" rows="4" style="width: 100%; max-width: 500px; padding: 3px; resize: vertical;"><?php echo htmlspecialchars($user_desc); ?></textarea>
                    </p>
                    
                    <p style="margin-bottom: 5px;"><label style="font-weight: bold;">Select Profile Picture:</label></p>
                    <div title="avatar-picker" style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px; border: 1px solid #DDD; padding: 10px; background: #FAFAF9; max-height: 220px; overflow-y: auto; margin-bottom: 20px;">
                        <?php
                        // Fixed loop range to completely account for avatar1.jpg all the way to avatar15.jpg without missing 4 and 5
                        for ($i = 1; $i <= 15; $i++) {
                            $avatar_name = "avatar" . $i . ".jpg";
                            $checked = ($_SESSION['profile_pic'] === $avatar_name) ? 'checked' : '';
                            echo '<label style="display: flex; flex-direction: column; align-items: center; cursor: pointer; border: 1px solid #E4E4E4; padding: 5px; background: #FFF;">';
                            echo '<img src="../images/' . $avatar_name . '" alt="Avatar ' . $i . '" style="width: 50px; height: 50px; object-fit: cover; margin-bottom: 4px; border: 1px solid #CCC;">';
                            echo '<input type="radio" name="profile_pic" value="' . $avatar_name . '" ' . $checked . '>';
                            echo '</label>';
                        }
                        ?>
                    </div>
                    
                    <p>
                        <button type="submit" style="font-weight: bold; padding: 4px 12px; cursor: pointer;">Save Changes</button>
                    </p>
                </form>
            </div>
        </section>

        <aside style="width: 240px; padding: 15px;">
            <div title="gray-box" style="border: 1px solid #D1C5C5; background: #FFF2F2; padding: 15px;">
                <h3 style="color: #CC0000; margin-top: 0;">Danger Zone</h3>
                <p style="font-size: 12px; color: #555;">Deleting your profile is permanent.</p>
                <form action="settings.php" method="POST" onsubmit="return confirm('WARNING: Are you completely sure?');">
                    <input type="hidden" name="delete_account" value="1">
                    <button type="submit" style="background: #CC0000; color: #FFF; border: 1px solid #990000; font-weight: bold; padding: 5px 10px; cursor: pointer; width: 100%;">Delete My Account</button>
                </form>
            </div>
        </aside>
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