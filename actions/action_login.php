<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();
    
    // Verify if the form fields are empty
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $session->addMessage('error', 'All fields are mandatory.');
        header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
        exit;
    }

    require_once(__DIR__ . '/../database/connection.db.php');
    require_once(__DIR__ . '/../database/authenticated_user.class.php');

    $db = getDatabaseConnection();

    try {
        $user = AuthenticatedUser::getUserWithPassword($db, $_POST['email'], $_POST['password']);
    
        if ($user) {
            $session->setId($user->getId());
            $session->setName($user->getFullName());
            $session->setEmail($user->getEmail());
            $session->addMessage('success', 'Successfully logged in.');

        } else { 
            $session->addMessage('error', 'Wrong email or password.');
        }

    } catch (PDOException $e) {
        $session->addMessage('error', 'Database error occured.');
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
?>  