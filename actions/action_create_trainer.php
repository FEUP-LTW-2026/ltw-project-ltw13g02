<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/trainer.class.php');

    $session = new Session();
    require_once(__DIR__ . '/../utils/role_change_guard.php');

    if (!$session->isLoggedIn() || !$session->isAdmin()) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $required = ['first-name', 'last-name', 'username', 'email', 'password', 'confirm-password', 'csrf_token'];
    foreach ($required as $f) {
        if (empty($_POST[$f])) {
            $session->addErrorMessage('Please fill in all required fields.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token. Please try again.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $firstName = trim((string) $_POST['first-name']);
    $lastName = trim((string) $_POST['last-name']);
    $username = trim((string) $_POST['username']);
    $email = trim((string) $_POST['email']);
    $password = (string) $_POST['password'];
    $confirm = (string) $_POST['confirm-password'];

    if (!preg_match('/^[a-zA-Z]+$/', $firstName)) {
        $session->addErrorMessage('First name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z]+$/', $lastName)) {
        $session->addErrorMessage('Last name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // TODO: check if this is valid.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $session->addErrorMessage('Invalid email address.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
        $session->addErrorMessage('Username can only contain letters, numbers and underscores.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // TODO: check it there's need for stronger password requirements.
    if (strlen($password) < 8) {
        $session->addErrorMessage('Password must be at least 8 characters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($password !== $confirm) {
        $session->addErrorMessage('Passwords do not match.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $db = getDatabaseConnection();

    try {
        if (!AuthenticatedUser::isUsernameAvailable($db, $username)) {
            $session->addErrorMessage('Username is already taken.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        if (!AuthenticatedUser::isEmailAvailable($db, $email)) {
            $session->addErrorMessage('Email is already in use.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $db->beginTransaction();

        $user = AuthenticatedUser::createUser($db, $firstName, $lastName, $email, $username, $password);

        $stmt = $db->prepare('INSERT INTO Trainer (id) VALUES (?)');
        $stmt->execute([$user->getId()]);

        $db->commit();

        $session->addSuccessMessage('Trainer account created successfully.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (PDOException $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Database error: ' . $e->getMessage());
        $session->addErrorMessage('Database error while creating trainer.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        $session->addErrorMessage('Unexpected error: ' . $e->getMessage());
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
?>
