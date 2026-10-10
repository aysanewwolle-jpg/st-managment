<?php
$activePage = 'login.php';
include __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirectTo('dashboard.php');
}

$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($login === '' || $password === '') {
        $errorMessage = 'Username/email and password are required.';
    } else {
        $mysqli = require __DIR__ . '/includes/db.php';
        $stmt = $mysqli->prepare('SELECT id, full_name, username, email, password_hash, role FROM users WHERE username = ? OR email = ? LIMIT 1');
        $stmt->bind_param('ss', $login, $login);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_username'] = $user['username'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_role'] = $user['role'];
                redirectTo('dashboard.php');
            }
        }

        $errorMessage = 'Invalid username/email or password.';
        $stmt->close();
        $mysqli->close();
    }
}
?>

<section class="section">
    <div class="container">
        <div class="form-card">
            <span class="eyebrow">Welcome Back</span>
            <h2>Login</h2>

            <?php if ($errorMessage): ?>
                <div class="alert alert-error"><?php echo sanitize($errorMessage); ?></div>
            <?php endif; ?>

            <form method="POST" data-validate="true">
                <div class="form-grid">
                    <div class="form-field full-width">
                        <label for="login">Username or Email</label>
                        <input type="text" id="login" name="login" required>
                    </div>
                    <div class="form-field full-width">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
                <p class="form-note">No account yet? <a href="register.php">Register here</a></p>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
