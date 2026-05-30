<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../../utils/session.php');
    require_once(__DIR__ . '/../../database/connection.db.php');
    require_once(__DIR__ . '/../../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../../utils/helpers.php');

    $session = new Session();

    $firstName = trim((string) $_POST['first-name']);
    $lastName = trim((string) $_POST['last-name']);
    $username = trim((string) $_POST['username']);
    $email = trim((string) $_POST['email']);
    $password = (string) $_POST['password'];
    $confirmPassword= (string) $_POST['confirm-password'];

    // Verify if the form fields are empty
    if (empty($firstName) || empty($lastName) || empty($email) || empty($username) || empty($password) || empty($confirmPassword)) {
        $session->addErrorMessage('All fields are mandatory.');
        redirectToPreviousPage();
    }

    // Validate 
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
        $session->addErrorMessage('Password must be at least 8 characters and contain uppercase, lowercase, a number, and a special character (!@#$%^&*).');
        redirectToPreviousPage();
    }

    if ($password !== $confirmPassword) {
        $session->addErrorMessage('Passwords do not match.');
        redirectToPreviousPage();
    }

    $db = getDatabaseConnection();

    if (!AuthenticatedUser::isEmailAvailable($db, $email)) {
        $session->addErrorMessage('That email is already in use.');
        redirectToPreviousPage();
    }

    if (!AuthenticatedUser::isUsernameAvailable($db, $username)) {
        $session->addErrorMessage('That username is already in use.');
        redirectToPreviousPage();
    }

    try {
        $user = AuthenticatedUser::createUser($db, $firstName, $lastName, $email, $username, $password);
        $session->generateCSRFToken();
        $session->setId($user->getId());
        $session->setRole($user->getRole());
        $session->setRoleChangeCount(AuthenticatedUser::getUserRoleChangeCount($db, $user->getId()));
        $session->setUsername($user->getUsername());
        $session->setEmail($user->getEmail());
        $session->addSuccessMessage('Successfully signed up!');
        header('Location: /pages/index.php');
        exit;

    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occurred.');
        redirectToPreviousPage();
    }
?>