<?php
$activePage = 'contact.php';
include __DIR__ . '/includes/header.php';

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '' || $email === '' || $subject === '' || $message === '') {
        $errorMessage = 'Please complete all fields before sending your message.';
    } elseif (!isValidEmail($email)) {
        $errorMessage = 'Please enter a valid email address.';
    } else {
        $mysqli = require __DIR__ . '/includes/db.php';
        $stmt = $mysqli->prepare('INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $name, $email, $subject, $message);
        if ($stmt->execute()) {
            $successMessage = 'Your message has been sent successfully.';
            $_POST = [];
        } else {
            $errorMessage = 'Something went wrong while sending your message.';
        }
        $stmt->close();
        $mysqli->close();
    }
}
?>

<section class="section">
    <div class="container content-wrap">
        <div class="content-box">
            <span class="eyebrow">Get in Touch</span>
            <h2>Contact the academic office</h2>
            <p>Need help with account access, student records, or result inquiries? Send us a message and we will get back to you as soon as possible.</p>
            <ul class="info-list">
                <li>Email: support@srms.edu</li>
                <li>Phone: +251 911 123 456</li>
                <li>Office Hours: Mon–Fri, 8:00 AM – 5:00 PM</li>
            </ul>
        </div>

        <div class="form-card">
            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo sanitize($successMessage); ?></div>
            <?php endif; ?>
            <?php if ($errorMessage): ?>
                <div class="alert alert-error"><?php echo sanitize($errorMessage); ?></div>
            <?php endif; ?>

            <form method="POST" data-validate="true">
                <div class="form-grid">
                    <div class="form-field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? sanitize($_POST['name']) : ''; ?>">
                    </div>
                    <div class="form-field">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? sanitize($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-field full-width">
                        <label for="subject">Subject</label>
                        <input type="text" id="subject" name="subject" required value="<?php echo isset($_POST['subject']) ? sanitize($_POST['subject']) : ''; ?>">
                    </div>
                    <div class="form-field full-width">
                        <label for="message">Message</label>
                        <textarea id="message" name="message" required><?php echo isset($_POST['message']) ? sanitize($_POST['message']) : ''; ?></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Send Message</button>
            </form>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
