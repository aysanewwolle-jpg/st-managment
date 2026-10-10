<?php
session_start();
date_default_timezone_set('Africa/Addis_Ababa');

const APP_NAME = 'Student Result Management System';
const BASE_URL = 'http://localhost/student-result-management-system';
const DB_HOST = '127.0.0.1';
const DB_USER = 'root';
const DB_PASS = '';
const DB_NAME = 'student_result_management';

function redirectTo(string $path): void
{
    header('Location: ' . BASE_URL . '/' . ltrim($path, '/'));
    exit;
}

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id']);
}

function isAdmin(): bool
{
    return isLoggedIn() && ($_SESSION['user_role'] ?? '') === 'admin';
}

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

function isValidEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isStrongPassword(string $password): bool
{
    return strlen($password) >= 8;
}

function gradeForScore(int $score): string
{
    if ($score >= 90) {
        return 'A+';
    }
    if ($score >= 80) {
        return 'A';
    }
    if ($score >= 70) {
        return 'B';
    }
    if ($score >= 60) {
        return 'C';
    }
    if ($score >= 50) {
        return 'D';
    }
    return 'F';
}

function getCurrentUser(): ?array
{
    if (!isLoggedIn()) {
        return null;
    }

    return [
        'id' => $_SESSION['user_id'],
        'full_name' => $_SESSION['user_name'],
        'username' => $_SESSION['user_username'],
        'email' => $_SESSION['user_email'],
        'role' => $_SESSION['user_role'],
    ];
}
