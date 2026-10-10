<?php
$activePage = 'index.php';
include __DIR__ . '/includes/header.php';
?>

<section class="page-hero">
    <div class="container hero-grid">
        <div class="hero-copy">
            <span class="eyebrow">Academic Excellence</span>
            <h1>Student Result Management System</h1>
            <p>Track performance, manage student records, and view results in one secure digital platform designed for efficient academic administration.</p>
            <div class="actions">
                <a href="register.php" class="btn btn-primary">Create Account</a>
                <a href="services.php" class="btn btn-secondary">Explore Features</a>
            </div>
        </div>

        <div class="hero-panel">
            <h3>System Snapshot</h3>
            <div class="metrics">
                <div class="metric">
                    <strong>1,250+</strong>
                    <span>Students</span>
                </div>
                <div class="metric">
                    <strong>42</strong>
                    <span>Courses</span>
                </div>
                <div class="metric">
                    <strong>96%</strong>
                    <span>Pass Rate</span>
                </div>
                <div class="metric">
                    <strong>24/7</strong>
                    <span>Access</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Key Features</h2>
            <p>Everything needed to manage academic results from enrollment to performance analysis.</p>
        </div>

        <div class="feature-grid">
            <article class="feature-card">
                <div class="feature-icon">01</div>
                <h3>Student Profiles</h3>
                <p>Secure storage for personal details, academic history, and enrollment information.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon">02</div>
                <h3>Result Entry</h3>
                <p>Admin can add and organize scores for multiple subjects, term tests, and final results.</p>
            </article>
            <article class="feature-card">
                <div class="feature-icon">03</div>
                <h3>Performance Insights</h3>
                <p>Students can understand their standing with grade summaries and performance trends.</p>
            </article>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
