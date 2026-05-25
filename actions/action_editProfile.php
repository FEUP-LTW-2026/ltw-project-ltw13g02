<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');
    require_once(__DIR__ . '/../utils/role_change_guard.php');

    $session = new Session();
    
    $firstName = $_POST['first-name'] ?? '';
    $lastName = $_POST['last-name'] ?? '';
    $username = $_POST['username'] ?? '';

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }

    if ($_POST['csrf_token'] !== $session->getCSRFToken()) {
        $session->addErrorMessage('Invalid CSRF token.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z]*$/', $firstName)) {
        $session->addErrorMessage('First name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z]*$/', $lastName)) {
        $session->addErrorMessage('Last name can only contain letters.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (!preg_match('/^[a-zA-Z0-9_]*$/', $username)) {
        $session->addErrorMessage('Username can only contain letters, numbers and underscores.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser(getDatabaseConnection(), $session->getId());

    if ($firstName) {
        $user->setFirstName($firstName);
    }

    if ($lastName) {
        $user->setLastName($lastName);
    }
    
    if ($username) {
        // Verify if the inserted username is already in use
        $stmt = $db->prepare('SELECT 1 FROM User WHERE username = ?');
        $stmt->execute([$username]);

        if (!AuthenticatedUser::isUsernameAvailable($db, $username) && $username !== $user->getUsername()) {
            $session->addErrorMessage('That username is already in use.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $user->setUsername($username);
    }

    try {
        $user->save($db);

        if ($_POST['biography'] && $session->isTrainer()) {
            require_once(__DIR__ . '/../database/trainer.class.php');
            Trainer::updateBiography($db, $session->getId(), $_POST['biography']);
        }

        $session->setUsername($user->getUsername());
        $session->addSuccessMessage('Profile updated successfully!');

        if ($session->isTrainer()) {
            header("Location: ../pages/trainer.php?id={$session->getId()}");
        } else {
            header('Location: ../pages/profile.php');
        }
        
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occured.');
    }
?>