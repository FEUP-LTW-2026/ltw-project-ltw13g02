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
        // TODO: check the password strenght
        return strlen($password) >= 8;
    }

    function validateText(string $text) : bool {
        return preg_match('/^[a-zA-Z]+$/', $text) === 1;
        
    }

    function validateTextBox(string $text): bool {
        return preg_match('/^[a-zA-Z0-9\s.,!?]+$/', $text) && strlen($text) <= 500;
    }

?>