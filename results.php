<?php
$activePage = 'results.php';
require_once __DIR__ . '/config.php';

if (!isLoggedIn()) {
    redirectTo('login.php');
}

$currentUser = getCurrentUser();
$mysqli = require __DIR__ . '/includes/db.php';

$successMessage = '';
$errorMessage = '';

if ($currentUser['role'] === 'admin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentId = (int) ($_POST['student_id'] ?? 0);
    $termName = trim($_POST['term_name'] ?? '');
    $courseName = trim($_POST['course_name'] ?? '');
    $score = (int) ($_POST['score'] ?? 0);
    $remark = trim($_POST['remark'] ?? '');

    if ($studentId <= 0 || $termName === '' || $courseName === '' || $score < 0 || $score > 100) {
        $errorMessage = 'Please provide valid result information.';
    } else {
        $stmt = $mysqli->prepare('INSERT INTO results (student_id, term_name, course_name, score, remark) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('issis', $studentId, $termName, $courseName, $score, $remark);
        if ($stmt->execute()) {
            $successMessage = 'Result added successfully.';
        } else {
            $errorMessage = 'Failed to add result.';
        }
        $stmt->close();
    }
}

$students = [];
if ($currentUser['role'] === 'admin') {
    $studentsResult = $mysqli->query('SELECT id, full_name, username FROM users WHERE role = "student" ORDER BY full_name ASC');
    while ($row = $studentsResult->fetch_assoc()) {
        $students[] = $row;
    }
}

if ($currentUser['role'] === 'student') {
    $stmt = $mysqli->prepare('SELECT * FROM results WHERE student_id = ? ORDER BY term_name ASC, course_name ASC');
    $stmt->bind_param('i', $currentUser['id']);
    $stmt->execute();
    $results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $results = $mysqli->query('SELECT r.*, u.full_name AS student_name FROM results r INNER JOIN users u ON u.id = r.student_id ORDER BY r.term_name ASC, r.course_name ASC')->fetch_all(MYSQLI_ASSOC);
}

include __DIR__ . '/includes/header.php';
?>

<section class="section">
    <div class="container">
        <div class="section-header">
            <h2><?php echo $currentUser['role'] === 'admin' ? 'Manage Results' : 'Your Results'; ?></h2>
        </div>

        <?php if ($currentUser['role'] === 'admin'): ?>
            <div class="form-card" style="margin-bottom: 2rem;">
                <?php if ($successMessage): ?>
                    <div class="alert alert-success"><?php echo sanitize($successMessage); ?></div>
                <?php endif; ?>
                <?php if ($errorMessage): ?>
                    <div class="alert alert-error"><?php echo sanitize($errorMessage); ?></div>
                <?php endif; ?>

                <form method="POST" data-validate="true">
                    <div class="form-grid">
                        <div class="form-field">
                            <label for="student_id">Student</label>
                            <select id="student_id" name="student_id" required>
                                <option value="">Select student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo (int) $student['id']; ?>"><?php echo sanitize($student['full_name']); ?> (<?php echo sanitize($student['username']); ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-field">
                            <label for="term_name">Term</label>
                            <input type="text" id="term_name" name="term_name" required>
                        </div>
                        <div class="form-field">
                            <label for="course_name">Course</label>
                            <input type="text" id="course_name" name="course_name" required>
                        </div>
                        <div class="form-field">
                            <label for="score">Score (0-100)</label>
                            <input type="number" id="score" name="score" min="0" max="100" required>
                        </div>
                        <div class="form-field full-width">
                            <label for="remark">Remark</label>
                            <input type="text" id="remark" name="remark" placeholder="Excellent, Good, Needs Improvement">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Result</button>
                </form>
            </div>
        <?php endif; ?>

        <div class="table-card">
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <?php if ($currentUser['role'] === 'admin'): ?>
                                <th>Student</th>
                            <?php endif; ?>
                            <th>Term</th>
                            <th>Course</th>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($results)): ?>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <?php if ($currentUser['role'] === 'admin'): ?>
                                        <td><?php echo sanitize($result['student_name']); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo sanitize($result['term_name']); ?></td>
                                    <td><?php echo sanitize($result['course_name']); ?></td>
                                    <td><?php echo (int) $result['score']; ?></td>
                                    <td><span class="badge <?php echo $result['score'] >= 50 ? 'good' : 'bad'; ?>"><?php echo gradeForScore((int) $result['score']); ?></span></td>
                                    <td><?php echo sanitize($result['remark']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo $currentUser['role'] === 'admin' ? '6' : '5'; ?>">No results available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
