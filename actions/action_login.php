<?php
    declare(strict_types=1);

    require_once(__DIR__ . '/../utils/session.php');

    $session = new Session();

    $email = $_POST['email'];
    
    if ($email) { // for now, accept any email, without DB interaction

        $session->setId(1); // fictitious id, used in isLoggedIn()
        $session->setEmail($email);
        $session->addMessage('success', 'Successfully logged in!');

    } else { 

        $session->addMessage('error', 'Email is mandatory.');
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']); // HTTP redirect to the previous page
?>