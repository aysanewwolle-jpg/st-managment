<?php
$activePage = 'register.php';
include __DIR__ . '/includes/header.php';

if (isLoggedIn()) {
    redirectTo('dashboard.php');
}

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $email === '' || $username === '' || $password === '' || $confirmPassword === '') {
        $errorMessage = 'All fields are required.';
    } elseif (!isValidEmail($email)) {
        $errorMessage = 'Please enter a valid email.';
    } elseif (!isStrongPassword($password)) {
        $errorMessage = 'Password must contain at least 8 characters.';
    } elseif ($password !== $confirmPassword) {
        $errorMessage = 'Passwords do not match.';
    } else {
        $mysqli = require __DIR__ . '/includes/db.php';
        $check = $mysqli->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
        $check->bind_param('ss', $email, $username);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errorMessage = 'A user with this email or username already exists.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $mysqli->prepare('INSERT INTO users (full_name, email, username, password_hash, role) VALUES (?, ?, ?, ?, ?)');
            $role = 'student';
            $stmt->bind_param('sssss', $fullName, $email, $username, $hash, $role);
            if ($stmt->execute()) {
                $successMessage = 'Registration successful. You can now log in.';
                $_POST = [];
            } else {
                $errorMessage = 'Registration failed. Please try again.';
            }
            $stmt->close();
        }
        $check->close();
        $mysqli->close();
    }
}
?>

<section class="section">
    <div class="container">
        <div class="form-card">
            <span class="eyebrow">Create Account</span>
            <h2>Register</h2>

            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo sanitize($successMessage); ?></div>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <div class="alert alert-error"><?php echo sanitize($errorMessage); ?></div>
            <?php endif; ?>

            <form method="POST" data-validate="true">
                <div class="form-grid">
                    <div class="form-field full-width">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required value="<?php echo isset($_POST['full_name']) ? sanitize($_POST['full_name']) : ''; ?>">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-field">
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required value="<?php echo isset($_POST['username']) ? sanitize($_POST['username']) : ''; ?>">
                    </div>
                    <div class="form-field">
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="form-field">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Register Now</button>
                <p class="form-note">Already have an account? <a href="login.php">Login here</a></p>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
