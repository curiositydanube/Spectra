<?php
session_start();

$host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'youtube_2006_db';

$status_message = "";

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);
    if (!$conn->connect_error) {
        $cookie_username = $_COOKIE['remember_user'];
        $stmt = $conn->prepare("SELECT id, username, profile_pic FROM users WHERE username = ?");
        $stmt->bind_param("s", $cookie_username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($user = $result->fetch_assoc()) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['profile_pic'] = $user['profile_pic'];
        }
        $stmt->close();
        $conn->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username'])) {
    $conn = new mysqli($host, $db_user, $db_pass, $db_name);

    if ($conn->connect_error) {
        $status_message = "Database connection failed: " . $conn->connect_error;
    } else {
        $username = trim($_POST['username']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $profile_pic = isset($_POST['profile_pic']) ? $_POST['profile_pic'] : 'avatar1.jpg';

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, profile_pic) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $password, $profile_pic);

        if ($stmt->execute()) {
            $new_id = $conn->insert_id;
            $private_id = 'PRIV-' . $new_id;

            $update_stmt = $conn->prepare("UPDATE users SET private_account_id = ? WHERE id = ?");
            $update_stmt->bind_param("si", $private_id, $new_id);
            $update_stmt->execute();
            $update_stmt->close();

            $_SESSION['user_id'] = $new_id;
            $_SESSION['username'] = $username;
            $_SESSION['profile_pic'] = $profile_pic;

            setcookie("remember_user", $username, time() + (86400 * 30), "/");

            $status_message = "Account created successfully! You are now logged in.";
        } else {
            $status_message = "Registration Error: " . $stmt->error;
        }
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spectra Video - Sign Up</title>
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
                        Hello, 
                        <a href="channel/<?php echo htmlspecialchars($_SESSION['username']); ?>" style="text-decoration: none; color: #0033CC; font-weight: bold;">
                            <?php echo htmlspecialchars($_SESSION['username']); ?>
                        </a>
                        | <a href="settings.php">Settings</a>
                        | <a href="index.php?action=logout">Log Out</a>
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

    <main>
        <section>
            <h2>Join the Spectra Video Community!</h2>
            
            <?php if (!empty($status_message)): ?>
                <div title="status-message" style="margin: 15px; padding: 8px; background: #FFF9D7; border: 1px solid #E2C822; font-weight: bold; color: #333;">
                    <?php echo htmlspecialchars($status_message); ?>
                </div>
            <?php endif; ?>

            <div title="form-container">
                <h3>Sign Up</h3>
                <form action="register.php" method="POST">
                    <p>
                        <label>Username:</label>
                        <input type="text" name="username" required>
                    </p>
                    <p>
                        <label>Email Address:</label>
                        <input type="email" name="email" required>
                    </p>
                    <p>
                        <label>Password:</label>
                        <input type="password" name="password" required>
                    </p>
                    
                    <p><label>Choose a Profile Picture:</label></p>
                    <div title="avatar-picker">
                        <?php
                        $avatars = ['avatar1.jpg', 'avatar2.jpg', 'avatar3.jpg', 'avatar4.jpg', 'avatar5.jpg'];
                        foreach ($avatars as $index => $avatar) {
                            $checked = ($index === 0) ? 'checked' : '';
                            echo '<label title="avatar-option">';
                            echo '<input type="radio" name="profile_pic" value="' . htmlspecialchars($avatar) . '" ' . $checked . '>';
                            echo '<img src="../images/' . htmlspecialchars($avatar) . '" alt="Avatar ' . ($index + 1) . '">';
                            echo '</label>';
                        }
                        ?>
                    </div>
                    
                    <p>
                        <button type="submit">Sign Up</button>
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