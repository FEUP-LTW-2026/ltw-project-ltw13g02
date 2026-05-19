<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    if (!$session->isLoggedIn()) {
        header('Location: ../pages/login.php');
        exit;
    }
    
    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');

    $db = getDatabaseConnection();
    $user = AuthenticatedUser::getUser(getDatabaseConnection(), $session->getId());

    if ($_POST['first-name']) {
        $user->setFirstName($_POST['first-name']);
    }

    if ($_POST['last-name']) {
        $user->setLastName($_POST['last-name']);
    }
    
    if ($_POST['username']) {

        // Verify if the inserted username is already in use
        $stmt = $db->prepare('SELECT 1 FROM User WHERE username = ?');
        $stmt->execute(([$_POST['username']]));

        if ($stmt->fetch()) {
            $session->addErrorMessage('That username is already in use.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $user->setUsername($_POST['username']);
    }

    try {
        $user->save($db);
        $session->setUsername($user->getUsername());
        $session->addSuccessMessage('Profile updated successfully!');
        header('Location: ../pages/profile.php');
        
    } catch (PDOException $e) {
        $session->addErrorMessage('Database error occured.');
    }
?>