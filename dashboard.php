<?php
$activePage = 'dashboard.php';
require_once __DIR__ . '/config.php';

if (!isLoggedIn()) {
    redirectTo('login.php');
}

$currentUser = getCurrentUser();
$mysqli = require __DIR__ . '/includes/db.php';

$summary = [];
if ($currentUser['role'] === 'admin') {
    $summaryQuery = $mysqli->query('SELECT COUNT(*) AS students FROM users WHERE role = "student"');
    $resultQuery = $mysqli->query('SELECT COUNT(*) AS results FROM results');
    $avgQuery = $mysqli->query('SELECT AVG(score) AS avg_score FROM results');

    $averageRow = $avgQuery ? $avgQuery->fetch_assoc() : null;

    $summary = [
        'students' => $summaryQuery ? $summaryQuery->fetch_assoc()['students'] : 0,
        'results' => $resultQuery ? $resultQuery->fetch_assoc()['results'] : 0,
        'avg_score' => $averageRow && $averageRow['avg_score'] !== null ? round((float) $averageRow['avg_score'], 2) : 0,
    ];
} else {
    $stmt = $mysqli->prepare('SELECT * FROM results WHERE student_id = ? ORDER BY term_name ASC');
    $stmt->bind_param('i', $currentUser['id']);
    $stmt->execute();
    $summary['rows'] = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2>Dashboard</h2>
            <p>Welcome, <?php echo sanitize($currentUser['full_name']); ?>.</p>
        </div>

        <?php if ($currentUser['role'] === 'admin'): ?>
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Total Students</h3>
                    <p><?php echo (int) $summary['students']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Published Results</h3>
                    <p><?php echo (int) $summary['results']; ?></p>
                </div>
                <div class="stat-card">
                    <h3>Average Score</h3>
                    <p><?php echo $summary['avg_score']; ?>%</p>
                </div>
            </div>

            <div class="content-box" style="margin-top: 2rem;">
                <h3>Admin Quick Actions</h3>
                <div class="actions">
                    <a href="results.php" class="btn btn-primary">Manage Results</a>
                    <a href="logout.php" class="btn btn-secondary">Logout</a>
                </div>
            </div>
        <?php else: ?>
            <div class="content-box">
                <h3>Your Result Summary</h3>
                <?php if (!empty($summary['rows'])): ?>
                    <div class="table-wrap">
                        <table>
                            <thead>
                                <tr>
                                    <th>Term</th>
                                    <th>Course</th>
                                    <th>Score</th>
                                    <th>Grade</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($summary['rows'] as $row): ?>
                                    <tr>
                                        <td><?php echo sanitize($row['term_name']); ?></td>
                                        <td><?php echo sanitize($row['course_name']); ?></td>
                                        <td><?php echo (int) $row['score']; ?></td>
                                        <td><span class="badge <?php echo $row['score'] >= 50 ? 'good' : 'bad'; ?>"><?php echo gradeForScore((int) $row['score']); ?></span></td>
                                        <td><?php echo sanitize($row['remark']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p>No results have been published for your account yet.</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
