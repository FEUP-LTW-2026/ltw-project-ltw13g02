<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    $required = ['first-name', 'last-name', 'username', 'email', 'password', 'confirm-password', 'csrf_token'];
    if (!validateRequiredFields($required)) {
        $session->addErrorMessage('All fields are required.');
        redirectToPreviousPage();
    }

    if (!$session->verifyCsrfToken($_POST['csrf_token'])) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        redirectToPreviousPage();
    }

    $firstName = trim((string) $_POST['first-name']);
    $lastName  = trim((string) $_POST['last-name']);
    $username  = trim((string) $_POST['username']);
    $email     = trim((string) $_POST['email']);
    $password  = (string) $_POST['password'];
    $confirm   = (string) $_POST['confirm-password'];

    if (!validateText($firstName)) {
        $session->addErrorMessage('First name can only contain letters.');
        redirectToPreviousPage();
    }

    if (!validateText($lastName)) {
        $session->addErrorMessage('Last name can only contain letters.');
        redirectToPreviousPage();
    }

    if (!validateEmail($email)) {
        $session->addErrorMessage('Invalid email address.');
        redirectToPreviousPage();
    }

    if (!validateUsername($username)) {
        $session->addErrorMessage('Username can only contain letters, numbers and underscores.');
        redirectToPreviousPage();
    }

    if (!validatePassword($password)) {
        $session->addErrorMessage('Password must be at least 8 characters.');
        redirectToPreviousPage();
    }

    if ($password !== $confirm) {
        $session->addErrorMessage('Passwords do not match.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    try {
        if (!AuthenticatedUser::isUsernameAvailable($db, $username)) {
            $session->addErrorMessage('Username is already taken.');
            redirectToPreviousPage();
        }

        if (!AuthenticatedUser::isEmailAvailable($db, $email)) {
            $session->addErrorMessage('Email is already in use.');
            redirectToPreviousPage();
        }

        $db->beginTransaction();

        $user = AuthenticatedUser::createUser($db, $firstName, $lastName, $email, $username, $password);

        $stmt = $db->prepare('INSERT INTO Member (id) VALUES (?)');
        $stmt->execute([$user->getId()]);

        $db->commit();

        $session->addSuccessMessage('User account created successfully.');
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Database error while creating user.');
    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Unexpected error: ' . $e->getMessage());
    }

    redirectToPreviousPage();
?>
