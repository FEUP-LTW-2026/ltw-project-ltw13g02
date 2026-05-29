<?php 
    declare(strict_types=1);

    function getProfileImageUrl(?int $userId): string {
        return file_exists('../img/profiles/' . $userId . '.jpg')
        ? '../img/profiles/' . $userId . '.jpg'
        : '../img/profiles/default.jpg';
    }

    function redirectToPreviousPage() {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    function redirectToHomePage() {
        header('Location: ../pages/index.php');
        exit;
    }

    function redirectToLogin() {
        header('Location: ../pages/login.php');
        exit;
    }

    function validateEmail(string $email) : bool {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    function validateUsername(string $username) : bool {
        return preg_match('/^[a-zA-Z0-9_]+$/', $username) === 1;
    }

    function validatePassword(string $password) : bool {
        // Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, one digit, and one special character
        return preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/', $password) === 1;
    }

    function validateText(string $text) : bool {
        return preg_match('/^[a-zA-Z]+$/', $text) === 1;
    }

    function validateTextBox(string $text): bool {
        return preg_match('/^[a-zA-Z0-9\s.,!?-]+$/', $text) && strlen($text) <= 500;
    }

    function validateRequiredFields(array $fields): bool {
        foreach ($fields as $field) {
            if (!isset($_POST[$field]) || $_POST[$field] === '') {
                return false;
            }
        }
        return true;
    }
?>