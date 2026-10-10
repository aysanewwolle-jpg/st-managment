<?php
$activePage = 'services.php';
include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Services & Features</h2>
            <p>Designed around a complete academic lifecycle and real database operations.</p>
        </div>

        <div class="cards-grid">
            <article class="info-card">
                <h3>Student Registration</h3>
                <p>New users can create accounts with secure password handling and duplicate checks.</p>
            </article>
            <article class="info-card">
                <h3>Login & Session Control</h3>
                <p>Session-based authentication restricts access to protected pages and keeps users logged in.</p>
            </article>
            <article class="info-card">
                <h3>Result Management</h3>
                <p>Admins can add exam scores, calculate grades, and store them safely in the database.</p>
            </article>
            <article class="info-card">
                <h3>Academic Dashboard</h3>
                <p>Users can view current performance, GPA trends, and subject-based results in one place.</p>
            </article>
            <article class="info-card">
                <h3>Contact Support</h3>
                <p>Students can reach out through the contact form for help, inquiries, or support requests.</p>
            </article>
            <article class="info-card">
                <h3>Secure Database</h3>
                <p>Relational storage ensures data integrity, role separation, and persistent academic record management.</p>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
