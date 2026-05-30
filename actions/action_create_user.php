<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../utils/helpers.php');
    require_once(__DIR__ . '/../database/roleManager.class.php');

    $session = new Session();

    require_once(__DIR__ . '/../utils/protected_file_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        redirectToLogin();
    }

    $required = ['first-name', 'last-name', 'username', 'email', 'password', 'confirm-password', 'csrf_token', 'role'];
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
    $role      = (string) $_POST['role'];

    $availableRoles = RoleManager::getAllRoles();

    if (!in_array($role, $availableRoles)) {
        $session->addErrorMessage('Invalid role selected.');
        redirectToPreviousPage();
    }

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
        $session->addErrorMessage('Password must be at least 8 characters, contain at least one uppercase letter, one lowercase letter, one digit, and one special character.');
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


        $stmt = $db->prepare("INSERT INTO $role (id) VALUES (?)");
        $stmt->execute([$user->getId()]);

        $session->addSuccessMessage('Account created successfully.');
        $db->commit();
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Database error while creating user.');
    }

    redirectToPreviousPage();
?>
