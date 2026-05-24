<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../database/trainer.class.php');

    $session = new Session();

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
